#!/usr/bin/perl -w -s

#       get-ads.pl
#       
#       Copyright 2009 Rui Miguel Silva Seabra <rms@1407.org>
#       
#       This program is free software; you can redistribute it and/or modify
#       it under the terms of the GNU General Public License as published by
#       the Free Software Foundation; either version 3 of the License, or
#       (at your option) any later version.
#       
#       This program is distributed in the hope that it will be useful,
#       but WITHOUT ANY WARRANTY; without even the implied warranty of
#       MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#       GNU General Public License for more details.
#       
#       You should have received a copy of the GNU General Public License
#       along with this program; if not, write to the Free Software
#       Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
#       MA 02110-1301, USA.

use warnings;
#use strict;
use LWP::UserAgent;
use POE qw(Wheel::Run Filter::Reference);

my $REPO=$ARGV[1];

sub MAX_CONCURRENT_TASKS () { 4 }

$update = 0 unless $update;

my %USER_AGENTS = (
	konqueror	=> { count => 0, string => "Mozilla/5.0 (compatible; Konqueror/4.1; Linux) KHTML/4.1.4 (like Gecko)", },
	firefox		=> { count => 0, string => "Mozilla/5.0 (X11; U; Linux i686; pt-PT; rv:1.9.0.5) Gecko/2008121622 Ubuntu/8.10 (intrepid) Firefox/3.0.5", },
	galeon		=> { count => 0, string => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.16) Gecko/20080716 (Gentoo) Galeon/2.0.6", },
	epiphany	=> { count => 0, string => "Mozilla/5.0 (X11; U; Linux x86_64; en; rv:1.9.0.1) Gecko/20080528 Epiphany/2.22 Firefox/3.0", },
	iceweasel	=> { count => 0, string => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9b5) Gecko/2008042623 Iceweasel/3.0b5 (Debian-3.0~b5-3)", },
	links		=> { count => 0, string => "Links (6.9; Unix 6.9-astral sparc; 80x25)", },
	lynx		=> { count => 0, string => "Lynxy/6.6.6dev.8 libwww-FM/3.14159FM", },
	w3m		=> { count => 0, string => "w3m/0.5.2", },
);

my @USER_AGENTS = keys %USER_AGENTS;

my $MAX_ATTEMPTS = 10;

my @tasks = <>;
chomp @tasks;
#print $#tasks; exit;

POE::Session->create (
	inline_states => {
		_start => \&start_tasks,
		next_task   => \&start_tasks,
		task_result => \&handle_task_result,
		task_done   => \&handle_task_done,
		task_debug  => \&handle_task_debug,
		sig_child   => \&sig_child,
	}
);

sub start_tasks {
	my ( $kernel, $heap ) = @_[ KERNEL, HEAP ];
	
	while ( keys( %{ $heap->{task} } ) < MAX_CONCURRENT_TASKS ) {
		my $next_task = shift @tasks;
		last unless defined $next_task;

		print "Starting task for $next_task...\n";
		
		my $task = POE::Wheel::Run->new (
			Program => sub { if ($update && -e "$REPO/$next_task") { print "Skipping $next_task\n" } else { do_stuff($next_task) } },
			StdoutFilter => POE::Filter::Reference->new(),
			StdoutEvent  => "task_result",
			StderrEvent  => "task_debug",
			CloseEvent   => "task_done",
		);
		$heap->{task}->{ $task->ID } = $task;
		$kernel->sig_child( $task->PID, "sig_child" );
	}
}

sub do_stuff {
	my $task   = shift;
	my $filter = POE::Filter::Reference->new();

	my $try=0;
	my @msgs = ();

	my $ua = LWP::UserAgent->new;
	my $ua_choice = $USER_AGENTS[rand $#USER_AGENTS+1];
	$ua->agent($USER_AGENTS{$ua_choice}{string});

	#push @msgs,sprintf "%s => %d , %s\n",$ua_choice, $USER_AGENTS{$ua_choice}{count}, $ua->agent($USER_AGENTS{$ua_choice}{string});

	my $req = HTTP::Request->new(GET => "http://www.base.gov.pt/_layouts/ccp/AjusteDirecto/Detail.aspx?idAjusteDirecto=$task");

	my ($res,$STATUS);

	do {
		push @msgs, "Hms... bad Sharepoint! Bad! Attempt no. $try\n" if $try++ > 0;
		$res = $ua->request($req);
	} until ( ($res->is_success && $res->content !~ /Sem dados/msgi) or $try == $MAX_ATTEMPTS );

	if ( $res->is_success && $res->content !~ /Sem dados/msgi ) {
		open DOC,">$REPO/$task";
		print DOC $res->content;
		close DOC;

		$STATUS = 'OK';
		$STATUS.= " (after $try attempts)" if $try > 1;
	} else {
		$STATUS = 'NOT OK';
		$STATUS.=" (gave up after $try attempts)" if $try == $MAX_ATTEMPTS;
	}

	my $status_line = $res->status_line;
	

	my %result = (
		task => $task,
		status => "$STATUS: $status_line",
		messages => [ @msgs ],
		ua => $ua_choice,
	);

	my $output = $filter->put( [ \%result ] );
	print @$output;
}

sub handle_task_result {
	my $result = $_[ARG0];
	push @tasks,$result->{task} if $result->{status} =~ /NOT OK/;
	print "Result for $result->{task}: $result->{status}\n";
	for (@{$result->{messages}}) {
		print "MESSAGE FROM TASK $result->{task}: $_";
	}
	$USER_AGENTS{$result->{ua}}{count}++;
	#printf "%s => %d\n",$result->{ua}, $USER_AGENTS{$result->{ua}}{count};
}

sub handle_task_debug {
	my $result = $_[ARG0];
	print "Debug: $result\n";
}

sub handle_task_done {
	my ( $kernel, $heap, $task_id ) = @_[ KERNEL, HEAP, ARG0 ];
	delete $heap->{task}->{$task_id};
	$kernel->yield("next_task");
}

sub sig_child {
	my ( $heap, $sig, $pid, $exit_val ) = @_[ HEAP, ARG0, ARG1, ARG2 ];
	my $details = delete $heap->{$pid};
	# warn "$$: Child $pid exited";
}

$poe_kernel->run();

for (keys %USER_AGENTS) { printf "%s foi utilizado %d vezes.\n",$_, $USER_AGENTS{$_}{count}; }

exit 0;
