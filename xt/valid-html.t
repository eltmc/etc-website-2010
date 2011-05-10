#!/usr/bin/perl
use strict;
use warnings;
use Test::More tests => 26;
use FindBin qw($Bin);
use lib "$Bin/../t/lib";
use MyTest::Dirs;
use File::Find qw();
use File::Path qw(mkpath);
use File::Basename qw(dirname);
use Capture::Tiny qw(capture_merged);
use File::Slurp qw(read_file write_file);
use Digest::MD5 qw(md5_base64);
my $base_dir = File::Spec->abs2rel("$Bin/../html");


my %D = MyTest::Dirs->hash(
    data => [mementos => 'mementos'],
);

sub md5_file {
    my $file = shift;

    my $content = read_file $file;
    my $md5 = md5_base64 $content;

    # Add a trailing newline is here for convenience (we don't then
    # need to strip the newline from the memento file's md5 line to
    # compare like with like).
    return "$md5\n"; 
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

# NOTE: if the mementos get out of sync, just rm -rf data/valid-html/mementos/*

my @files = grep !m{/inc/}, grep /\.(html|php)$/, find {$_} $base_dir;

foreach my $file (@files) {
    (my $rel_path = $file) =~ s{^$base_dir/?}{};
    my $memento_file = "$D{mementos}/$rel_path";
    
    my $new_md5 = md5_file $file;

    if (-e $memento_file) {
        my ($old_md5, @mess) = read_file $memento_file;
        if ($old_md5 eq $new_md5) {
            # Then $file has not changed and it has been validated already
            
            # Add formatting if @mess contains anything, else set it to
            # the empty string.
            my $mess = join "", @mess;
            
            ok $mess !~ /\S/, 
                "validate $rel_path (unchanged, re-using last result)"
                    or diag $mess;
            next;
        }
    }    
     
    # If we get here, we need to validate
    my $rc;
    my $mess = capture_merged { $rc = system @cmd, $file };
    ok $rc == 0,
        "validate $rel_path"
            or diag $mess;
    
    # Now save the result for posterity
    
    mkpath dirname $memento_file;        
    write_file $memento_file, $new_md5, $mess;
}
