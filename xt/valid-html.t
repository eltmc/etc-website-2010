#!/usr/bin/perl
use strict;
use warnings;
use Test::More tests => 27;
use FindBin qw($Bin);
use lib "$Bin/../t/lib";
use MyTest::Dirs;
use File::Find qw();
use File::Path qw(mkpath);
use File::Basename qw(dirname);
use Capture::Tiny qw(capture_merged);
use File::Slurp qw(read_file write_file);
my $base_dir = File::Spec->abs2rel("$Bin/../html");


my %D = MyTest::Dirs->hash(
    data => [timestamps => 'timestamps'],
);

sub write {
    my $file = shift;


    open my $fh, ">", $file
        or die "failed to open $file: $!";
    
    print {$fh} @_;
    
    close $fh
        or die "failed to close $file: $!";
}


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

# This works (approximately) when using onsgmls directly.
#my @cmd = qw(onsgmls -s -n 
#             -c /usr/share/w3c-markup-validator/catalog/sgml.soc
#             -wvalid -wnon-sgml-char-ref -wno-duplicate -wxml -wfully-tagged);

# This works better (if you install wdg-html-validator).
my @cmd = qw(validate --w);

# NOTE: if the timestamps get out of sync, just rm -rf data/valid-html/timestamps/*

foreach my $file (grep /\.(html|php)$/, find {$_} $base_dir) {
    (my $rel_path = $file) =~ s{^$base_dir/?}{};
    my $timestamp_file = "$D{timestamps}/$rel_path";
    
 SKIP: {
        if (-e $timestamp_file 
            && -M $file >= -M $timestamp_file) {

            my $out = read_file $timestamp_file;
            
            # Add formatting if $out contains anything, else set it to
            # the empty string.
            $out = $out && $out =~ /\S/?
                ":\n$out" : "";
                
            skip "$rel_path validated since last modification$out", 1;
        }

        my $start_time = time;
        my $rc;
        my $out = capture_merged { $rc = system @cmd, $file };
        ok $rc == 0,
            "validate $rel_path"
                and next;
        
#        diag $out;

        # Save the output and mark it with the time that the test actually 
        # read the file.
        mkpath dirname $timestamp_file;        
        write_file $timestamp_file, $out;
        utime $start_time, $start_time, $timestamp_file;
    }
}
