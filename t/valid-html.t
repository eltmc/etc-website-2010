#!/usr/bin/perl
use strict;
use warnings;
use Test::More tests => 1;
use FindBin qw($Bin);
use lib "$Bin/lib";

use MyTest::Dirs;

#my %D = MyTest::Dirs->hash(
#    data => [html => 'html'],
#    temp => [out => 'out'],
#);

my $base_dir = File::Spec->abs2rel("$Bin/../html");

use File::Find qw();
sub find(&@) {
    my ($code, @rest) = @_;

    my @results;
    File::Find::find +{ 
        wanted => sub { 
            my %opts = (
                path => $File::Find::name,
                dir => $File::Find::dir,
            );
            push @results, $code->(%opts);
        },
        no_chdir => 1,
    }, @rest;

    return @results;
}

my @cmd = qw(onsgmls -s -n 
             -c /usr/share/w3c-markup-validator/catalog/sgml.soc
             -wvalid -wnon-sgml-char-ref -wno-duplicate -wxml -wfully-tagged);

@cmd = qw(validate --w);

foreach my $file (grep /\.html$/, find {$_} $base_dir) {
    ok 0==system(@cmd, $file),
        "validate $file";
}
