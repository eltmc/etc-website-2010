#!/usr/bin/perl
use strict;
use warnings;
use Test::More;
use FindBin qw($Bin);
use File::Find ();
use File::Slurp qw(read_file);
use lib "$Bin/lib";
use Test::Differences; unified_diff;

use MyTest::Dirs;

my %D = MyTest::Dirs->hash(
    data => [html => 'html'],
    temp => [out => 'out'],
);

my $cfg = "$Bin/../ttree.cfg";
system qq(cd $Bin/..; make --quiet DEST=$D{out} html);

sub find {
    my @files;

    foreach my $dir (@_) {
        File::Find::find { 
            wanted => sub { 
                return if -d;
#                return if /~$/;
                #        return if m{
                s{^$dir/?}{};
                push @files, $_;
            },
            no_chdir => 1,
        }, $dir;
    }

    return sort @files;
}

my @got_files = find $D{out};
my @expected_files = find $D{html};
#die explain \@got_files, \@expected_files;;

plan tests => @expected_files*2 + 1;

eq_or_diff [map("$_\n", @got_files)], [map("$_\n", @expected_files)],
    "got the expected file list"
    or exit;
    
foreach my $file (@expected_files) {   
    my ($got_type, $got, $expected_type, $expected) = map {
        my $path = "$_/$file";
        -f $path? ('file', scalar read_file $path) :
        -l $path? ('symlink', readlink $path) :
        -d $path? ('dir', '') :
        -e $path? ('unknown', '') :
        ('non-existant', '');
    } @D{'out', 'html'};

    is $got_type, $expected_type,
        "$file has type '$expected_type'"
            and eq_or_diff $got, $expected,
                "$file content matches";
}



#ok 0==system(qq(diff -Bwur -x messageboard $D{html} $D{out})),
#    "generated html has not changed significantly"; 

