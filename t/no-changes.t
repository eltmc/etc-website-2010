#!/usr/bin/perl
use strict;
use warnings;
use Test::More tests => 1;
use FindBin qw($Bin);
use lib "$Bin/lib";

use MyTest::Dirs;

my %D = MyTest::Dirs->hash(
    data => [html => 'html'],
    temp => [out => 'out'],
);

my $cfg = "$Bin/../ttree.cfg";

system qq(cd $Bin/..; ttree -f $cfg --dest $D{out});

ok 0==system(qq(diff -Bwur $D{html} $D{out})),
    "generated html has not changed significantly"; 

