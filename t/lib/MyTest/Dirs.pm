package MyTest::Dirs;
use strict;
use warnings;
use FindBin qw($Bin $Script);
use File::Spec;
use File::Glob qw(bsd_glob);
use File::Path qw(mkpath rmtree);
use Carp qw(croak);

=head2 C<< $obj = $class->new(%params) >>

Given parameters including:

  base => $base_dir,

  data => [ddir1 => relpath3, ddir2 => relpath4 ...]

  test => [tdir1 => relpath1, tdir2 => relpath2 ...]

Uses C<base> as a base dir in which to find data dirs C<relpathN> (which
are checked to exist), and in which to re-create fresh test dirs
C<relpathM>.

If C<base> is not given, uses the name of the invoking script, with
any leading digits or periods stripped, and any trailing ".t"
stripped.

Retuns a hash-based object which keys the names C<ddirN> and C<tdirN>
to the appropriate paths constructed from C<$base_dir> and the
appropriate C<relpath>.

=cut
                               
sub new {
    my $class = shift;
    my %param = @_;
    my $base = $param{base};
    ($base) = $Script =~ /^([\d.]*.*?)(\.t)?$/
        unless defined $base;

    my $self = bless {
        data_dir => File::Spec->catdir($Bin,'data', $base),
        temp_dir => File::Spec->catdir($Bin,'temp', $base),
        dirs => {},
        data_dirs => [],
        temp_dirs => [],
        copy => $param{copy} || [],
    }, $class;

    # expand the data directories 
    if (my $data = $param{data}) {
        for(my $ix = 0; $ix < @$data; $ix += 2) {
            my ($name, $dir) = @$data[$ix, $ix+1];
            die "Can't use dir name '$name': already in use as '$self->{$name}'"
                if exists $self->{dirs}{$name};

            $dir = File::Spec->catdir($self->{data_dir}, $dir);
            $self->{dirs}{$name} = $dir;
            push @{ $self->{data_dirs} }, $name, $dir;
        }
    }

    # ditto the temp directories
    if (my $temp = $param{temp}) {
        for(my $ix = 0; $ix < @$temp; $ix += 2) {
            my ($name, $dir) = @$temp[$ix, $ix+1];
            croak "Can't use dir name '$name': already in use as '$self->{$name}'"
                if exists $self->{dirs}{$name};

            $dir = File::Spec->catdir($self->{temp_dir}, $dir);
            $self->{dirs}{$name} = $dir;
            push @{ $self->{temp_dirs} }, $name, $dir;
        }
    }

    # check the copies attribute
    my $copy = $self->{copy};
    croak "copy param must be an arrayref with an even number of members"
        unless ref $copy eq 'ARRAY' 
            && !(@$copy % 2);

    $self->initialise();

    return $self;
}


sub initialise {
    my $self = shift;

    rmtree $self->{temp_dir};

    # validate the data directories exist
    my $data = $self->{data_dirs};
    for(my $ix = 0; $ix < @$data; $ix += 2) {
        my ($name, $dir) = @$data[$ix, $ix+1];
        croak "No such data directory '$dir'"
            unless -d $dir;
    }

    # recreate the temp directories
    my $temp = $self->{temp_dirs};
    for(my $ix = 0; $ix < @$temp; $ix += 2) {
        my ($name, $dir) = @$temp[$ix, $ix+1];
        
        rmtree $dir if -e $dir;
        croak "Can't delete '$dir'"
            if -e $dir;
        mkpath $dir;
        croak "Can't create '$dir'"
            unless -d $dir;
    }

    # perform any copies
    my $copy = $self->{copy};
    for(my $ix = 0; $ix < @$copy; $ix += 2) {
        my ($from, $to) = @$copy[$ix, $ix+1];
        $self->copy($from, $to);
    }
}

sub dirs { 
    croak "->dirs accepts no arguments"
        if @_ > 1;
    return shift->{dirs};
}

sub dir {
    my ($self, $alias) = @_;
    croak "you must supply a directory alias"
        unless defined $alias;
    my $dir = $self->{dirs}{$alias}
        or croak "No directory defined for alias '$alias'";

    return $dir;
}

sub hash { 
    my $arg = shift;
    my $self = ref $arg?
        $arg : 
        $arg->new(@_);

    return %{ $self->dirs };
}


sub copy {
    my $self = shift;
    my $to = pop;

    # expand/validate the from aliases
    my @from_paths = map { 
        my ($dir, $pat) = m{^ (.*?) (?: /(.*) )? $}x;
        [$self->dir($dir), $pat];
    } @_; 

    # validate the to aliases
    my $to_dir = $self->dir($to);

    require File::Copy;
    require File::Glob;

    my $count = 0;
    foreach my $item (@from_paths) {
        my ($from_dir, $pat) = @$item;

        $pat = "*" 
            unless defined $pat;

        $pat = File::Spec->catdir($from_dir, $pat);

        # We use bsd_glob because unlike glob, it doesn't (why oh
        # why?) use spaces as pattern delimiters
        foreach my $src (File::Glob::bsd_glob $pat) {
            my $path = File::Spec->abs2rel($src, $from_dir);
            my $dst = File::Spec->catdir($to_dir, $path);

            if (-f $src) { # Copy files
                File::Copy::copy($src, $dst)
                        or croak "failed to copy '$src' to '$dst': $!";
            }
            elsif (-d $src) { # Create directories
                mkpath $dst;
            }
            elsif (-l $src) { # Duplicate links
                symlink readlink($src), $dst
                    or croak "failed to copy link '$src' as '$dst': $!";
            }
            elsif (!-e $src) { # the src doesn't exist
                croak "cannot copy $src: no such file";
            }
            else { # We don't know how to do the right thing
                croak "cannot copy $src: unsupported file type";
            }
        }
        $count++;
    }

    return $count;
}

no Carp;
no File::Path;
1;
