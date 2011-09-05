#!/usr/bin/perl
use strict;
use warnings;
use Test::More tests => 2;
use HTML::LinkExtractor;
use FindBin qw($Bin);
use Cwd qw(abs_path);
use File::Spec;
use List::Util qw(first);
use File::Find;
use Data::Dumper;

my $base_dir = abs_path "$Bin/../html";

my @exclude = qw(/inc/);

my $unreachable_whitelist = [
    '.htpasswd',
    'documents/2011-07-08.membership-and-renewal-form.pdf', # this is actually symlinked

    # These are reachable, but invisibly (currently) because they are referenced by stylesheets. 
    # Ideally this test would ferret out stylesheet links too.
    'images/bottle.gif',
    'images/info.gif',
    'images/money.gif',
    'images/outandabout.gif',
    'images/pram.gif',
];


my $problem_whitelist = {
    'events/index.php' => {
        'mailto:social@edinburghtwins.co.uk' => { 
            'non-http scheme' => 1,
        },
    },
    'groups/index.php' => {
        'mailto:firstyears@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:webmaster@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:1styears.oxgangs@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:1styears.stjohns@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:oxgangs@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:westlothan@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:westedin@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:joppa@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:comelybank@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        '../messageboard/viewforum.php?f=5' => {
            'link outside base dir: ../messageboard/viewforum.php' => 1
        },
        '../messageboard/viewforum.php?f=7' => {
            'link outside base dir: ../messageboard/viewforum.php' => 1
        }
    },
    'messageboard.php' => {
        'messageboard/' => {
            'link outside base dir: ../messageboard' => 1
        },
        'mailto:webmaster@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
    },
    'tips/goodbuys/index.php' => {
        '../../messageboard' => {
            'link outside base dir: ../messageboard' => 1
        }
    },
    'tips/prams/index.php' => {
        '../../messageboard' => {
            'link outside base dir: ../messageboard' => 1
        }
    },
    'triplets/index.php' => {
        '../messageboard?f=6' => {
            'link outside base dir: ../messageboard' => 1
        },
        'mailto:triplets@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
    },
    'contacts/index.php' => {
        'mailto:newsletter@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:firstyears@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:1styears.oxgangs@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:1styears.stjohns@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:membership@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:webmaster@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:chair@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:social@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:oxgangs@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:westedin@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:joppa@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        },
        'mailto:comelybank@edinburghtwins.co.uk' => {
            'non-http scheme' => 1
        }
    }
};

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
    my ($src_file_path, $dir, $link) = @_;
    return unless my ($tag, $attr, $uri) = has_uri $link;

    $uri = $uri->canonical;

    my $info = {
        uri => "$uri",
    };

    if (my $scheme = $uri->scheme) {
        if ($scheme !~ /^https?$/) {
            $info->{problem} = "non-http scheme";
            return $info;
        }

        if ($uri->can('host') and my $host = $uri->host) {
            $info->{problem} = "absolute url linking to self"
                if $host =~ /^(www[.])?\Qedinburghtwins.co.uk\E$/;
        }
        else {
            $info->{problem} = "scheme url with no host";
        }
        
        return $info;
    }

    # Uri has no scheme
    my $raw_path = $uri->path
        or warn "no path in $uri from $src_file_path\n";
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

    my $rel_file_path = File::Spec->abs2rel(abs_path($file_path), $base_dir);
    if ($rel_file_path =~ /^[.][.]/) {
        $info->{problem} = "link outside base dir: $rel_file_path";
        return $info;
    }

    if (-d $file_path) {
        $rel_file_path = first { 
            -f "$base_dir/$_";
        } map { 
            File::Spec->canonpath("$rel_file_path/index.$_"); # get rid of leading ./
        } qw(html htm php);

        if (!$rel_file_path) {
            $info->{problem} = "link to directory with no index: $path";
            return $info;
        }
    }
#print ">>>$rel_file_path\n";

    return {
        uri => $rel_file_path,
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
    my ($path, $dir) = ($File::Find::name, $File::Find::dir);

    # Skip symlinks
    return $File::Find::prune = 1
        if -l $path;

    # Skip excluded paths
    return $File::Find::prune = 1
        if first { $path =~ m{$_} } @exclude;

    # Skip non-files
    return
        unless -f $path;

    # Mark this file as present (if not already marked as linked to)
    my ($internal_path) = $path =~ m{^$base_dir/(.*)};
    $SUBSCRIBERS{$internal_path} ||= {};

    # Don't try to parse anything but HTML files
    return unless $path =~ /\.(html|php)$/;

    # Parse the document, extract the links
    $lx->parse($path);
    my $links = $lx->links;

    my %problem_links;
    foreach my $link (@$links) {
        # skip "links" with no URI
        next 
            unless my $info = classify_link $path, $dir, $link;

        # index links with a problem
        if (my $problem = $info->{problem}) {
            my $uri = $info->{uri};
            $problem_links{$uri}{$problem} = 1;
            next;
        }

        my $target = URI->new($info->{uri});

        # Skip links with a schema, assumed to be absolute or 
        # external
        next
            if $target->scheme;

        # Add this link to the target file's subscriber list
        my $subscribers = $SUBSCRIBERS{$target->path} ||= {};
        $subscribers->{$internal_path}++;
    }

    # save the sorted list
    $PROBLEMS{$internal_path} = \%problem_links;
}


######################################################################
# Main code

# Populate %SUBSCRIBERS and %PROBLEMS
find +{ 
    wanted => \&validate_html,
    no_chdir => 1,
}, $base_dir;


# Are there any problem links?    
my $problem_links = {map { my $p = $PROBLEMS{$_}; %$p? ($_ => $p) : () } keys %PROBLEMS}; 
is_deeply $problem_links, $problem_whitelist,
    "no new problems"
    or diag Dumper $problem_links;

# Are there any unreachable files?
my $unreachable = [grep {!%{  $SUBSCRIBERS{$_} } } sort keys %SUBSCRIBERS];
is_deeply $unreachable, $unreachable_whitelist, 
    "no new unreachable files"
    or diag Dumper $unreachable;


