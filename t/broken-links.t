#!/usr/bin/perl
use strict;
use warnings;
use Test::More tests => 1;
use HTML::LinkExtractor;
use FindBin qw($Bin);
use Cwd qw(abs_path);
use File::Spec;
use List::Util qw(first);
use File::Find;
use Data::Dumper;
my $base_dir = File::Spec->abs2rel(abs_path "$Bin/../html");


my %PROBLEMS;
my %SUBSCRIBERS;


my $lx = HTML::LinkExtractor->new;
#$lx->strip(1);

######################################################################
# helper functions

=head2 C<($tag, $attr, $uri) = has_uri $link>

Given a C<$link> hash as returned by C<<HTML::LinkExtractor->parse>>,
returns a triplet of values if the link in question is the sort that
has a URI, based on C<%HTML::LinkExtractor::TAGS>.  Otherwise the
empty list.

The return values are:

=over 4

=item C<$tag>

the name of the tag the C<$link> represents

=item C<$attr>

the name of the first attribute from the tag's entry in
C<%HTML::LinkExtractor::TAGS> that has a link associated.

=item C<$uri>

The actual link uri. This will be an instance of the C<URI> class.

=back

=cut

sub has_uri {
    my $link = shift;
    my $tag = $link->{tag};
    return unless my $attrs = $HTML::LinkExtractor::TAGS{$tag};
    my $attr = first { $link->{$_} } @$attrs;
    return unless $attr;
    return ($tag, $attr, URI->new($link->{$attr}));
}


=head2 C<$link_info = classify_link $base_dir, $link>

Classifies the C<HTML::LinkExtractor> link C<$link>.  

If the link is not one with a URI, returns the empty list.

Otherwise, it returns a hashref with one or more fields:

=over 4

=item C<uri>

Always present - the URI object for the link.  In the case that the
link is to document internal to the site and has no problems, the URI
will be a full file path to the linked object and the URI will have no
scheme. Otherwise it will be the original link.

=item C<problem>

A short description of why the link is defective.  Not present if the
link seems ok.

=back

=cut

sub classify_link {
    my ($dir, $link) = @_;
    return unless my ($tag, $attr, $uri) = has_uri $link;
    
    $uri = $uri->canonical;

    my $info = {
        uri => $uri,
    };

    if (my $scheme = $uri->scheme) {
        if ($scheme !~ /^https?$/) {
            $info->{problem} = "non-http scheme";
            return $info;
        }

        my $host = $uri->host;
        if ($host) {
            $info->{problem} = "absolute url linking to self"
                if $host =~ /^(www[.])?\Qedinburghtwins.co.uk\E$/;
        }
        else {
            $info->{problem} = "http scheme url with no host";
        }
        
        return $info;
    }

    # Uri has no scheme
    my $raw_path = $uri->path;
    my ($extended_path, $query) = split /[?]/, $raw_path, 2;
    my ($path, $anchor) = split /#/, $extended_path, 1;

    if ($path =~ m{^/}) {
        $info->{problem} = "absolute path";
        return $info;
    }

    my $file_path = File::Spec->catdir($dir, $path);
    if (!-e $file_path) {
        $info->{problem} = "broken link";
        return $info;
    }

    $file_path = File::Spec->abs2rel(abs_path($file_path));
    if ($file_path !~ /^$base_dir/) {
        $info->{problem} = "link outside base dir: $file_path";
        return $info;
    }
    
    if (-d $file_path) {
        $file_path = first { -f } map { "$file_path/index.$_" } qw(html htm php);
        if (!$file_path) {
            $info->{problem} = "link to directory with no index: $path";
            return $info;
        }
    }

#    print "<$tag $attr>: $uri\n";
    return {
        uri => URI->new($file_path),
    };
}


=head2 C<>

A callback designed to be invoked by C<File::Find::find>.  It skips
all but .html files.  These files it parses using
C<HTML::LinkExtractor>, and classifies all the links.  The list of
classified links is added to C<$links{$name}>, sorted by their
string-ified uri keys.

=cut

sub validate_html {
    my ($name, $dir) = ($File::Find::name, $File::Find::dir);

    # Skip non-files
    return
        unless -f $name;

    # Mark this file as present (if not already marked as linked to)
    $SUBSCRIBERS{$name} ||= {};

    # Don't try to parse anything but HTML files
    return unless $name =~ /\.html$/;

    # Parse the document, extract the links
    $lx->parse($name);
    my $links = $lx->links;

    my @problem_links;
    foreach my $link (@$links) {
        # skip "links" with no URI
        next 
            unless my $info = classify_link $dir, $link;

        # index links with a problem
        if ($info->{problem}) {
            push @problem_links, $info;
            next;
        }

        my $target = $info->{uri};

        # Skip links with a schema, assumed to be absolute or 
        # external
        next
            if $target->scheme;

        # Add this link to the target file's subscriber list
        my $subscribers = $SUBSCRIBERS{$target->path} ||= {};
        $subscribers->{$name}++;
    }

    # save the sorted list
    $PROBLEMS{$name} = [sort { "".$a->{uri} cmp "".$b->{uri} } @problem_links];
}


######################################################################
# Main code

# Populate %SUBSCRIBERS and %PROBLEMS
find +{ 
    wanted => \&validate_html,
    no_chdir => 1,
}, $base_dir;


# Are there any problem links?    
is_deeply \%PROBLEMS, {},
    "no new problems"
    or diag Dumper \%PROBLEMS;

# Are there any unreachable files?
my $unreachable = [grep {!%{  $SUBSCRIBERS{$_} } } sort keys %SUBSCRIBERS];
is_deeply $unreachable, [], 
    "no new unreachable files"
    or diag Dumper $unreachable;

