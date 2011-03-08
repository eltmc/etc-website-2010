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
my $base_dir = File::Spec->abs2rel(abs_path "$Bin/../html");


my %links_to_pages;


my $lx = HTML::LinkExtractor->new;
#$lx->strip(1);

sub has_uri {
    my $link = shift;
    my $tag = $link->{tag};
    return unless my $attrs = $HTML::LinkExtractor::TAGS{$tag};
    my $attr = first { $link->{$_} } @$attrs;
    return unless $attr;
    return ($tag, $attr, URI->new($link->{$attr}));
}

sub valid_link {
    my ($dir, $link) = @_;
    return unless my ($tag, $attr, $uri) = has_uri $link;
    
    $uri = $uri->canonical;
    # foreach my $case (@cases) {
    #     my $classification = $case->{validate}->($tag, $attr, $uri);

    #     next unless $classification;
    #     return unless ref $classification;

    #     printf $case->{format}, @$classification;
    # }

    if (my $scheme = $uri->scheme) {
        if ($scheme !~ /^https?$/) {
            print "non-http scheme: $uri\n";
            return;
        }

        my $host = $uri->host;
        return $uri
            if $host 
            && $host !~ /^(www[.])?\Qedinburghtwins.co.uk\E$/;
        
        print "absolute url linking to self: $uri\n";
        return;
    }

    # Uri has no scheme
    my $raw_path = $uri->path;
    my ($extended_path, $query) = split /[?]/, $raw_path, 2;
    my ($path, $anchor) = split /#/, $extended_path, 1;

    if ($path =~ m{^/}) {
        print "absolute path: $uri\n";
        return;
    }

    my $file_path = File::Spec->catdir($dir, $path);
    if (!-e $file_path) {
        print "broken link: $uri\n";
        return;
    }

    $file_path = File::Spec->abs2rel(abs_path($file_path));
    if ($file_path !~ /^$base_dir/) {
        print "link outside base dir: $file_path ($uri)\n";
        return;
    }
    
    if (-d $file_path) {
        $file_path = first { -f } map { "$file_path/index.$_" } qw(html htm php);
        if (!$file_path) {
            print "link to directory with no index: $path\n";
            return;
        }
    }

#    print "<$tag $attr>: $uri\n";
    return URI->new($file_path);
}


sub validate_html {
    my ($name, $dir) = ($File::Find::name, $File::Find::dir);

    return unless $name =~ /\.html$/;

   
    print "--- validating $name\n";

    $lx->parse($name);
    my $links = $lx->links;

    $links_to_pages{$name} ||= [];

    foreach my $link (@$links) {
        next 
            unless my $target = valid_link $dir, $link;

        next
            if $target->scheme;

        my $valid_links = $links_to_pages{$target->path} ||= [];

        push @$valid_links, $link;
    }
}

find +{ 
    wanted => \&validate_html,
    no_chdir => 1,
}, $base_dir;


print "possibly unreachable pages:\n";
foreach my $page (sort keys %links_to_pages) {
    my $links = $links_to_pages{$page};
    print "  $page\n"
        unless @$links;
}




