#!/usr/bin/perl -w

my $url_list="http://www.base.gov.pt/_layouts/ccp/AjusteDirecto/List.aspx";

my $retries=6;
my $max_sleep_retries=3;
my $sleep_interval=10;
my %ADs;

my $debug = 0;

use LWP::UserAgent;
use HTTP::Cookies;

sub get_url {
	my ($url, $ua) = @_;

	print "Getting url: $url...\n" if $debug;

	my $url_response = $ua->get($url);
	my $i = 1;
	my $j = 1;

	while(!$url_response->is_success && $i++ <= $retries ) {
		print "Getting url (attempt $i): $url...\n" if $debug;
		$url_list_response = $ua->request($url_list);
		if($i++ <= $retries) {
			$i = 1;
			last if $j++ <= $max_sleep_retries;
			sleep($sleep_interval);
		}
	}
	if($url_response->is_success) {
		return($url_response->content);
	} else { return undef }
}

sub get_ads {
	my ($page, $page_number) = @_;

	for ($page =~ m{'Detail.aspx\?idAjusteDirecto=(\d+)' .*?\>.*?\<}msig) {
		if (exists $ADs{$_}) {
			$ADs{$_} = [$ADs{$_},$page_number];
		} else {
			$ADs{$_} = [$page_number];
		}
	}
}

my $ua = LWP::UserAgent->new;

$ua->timeout(10);
$ua->env_proxy;

$ua->cookie_jar(HTTP::Cookies->new);

$ua->agent("Mozilla/5.0 (X11; U; Linux i686; pt-PT; rv:1.9.0.5) Gecko/2008121622 Ubuntu/8.10 (intrepid) Firefox/3.0.5");

my $url_list_response = get_url($url_list, $ua);

if(defined $url_list_response) {
	if ( $url_list_response =~ m{id="ctl00_PlaceHolderMain_hddnPageLast" value="(.*?)"}msi ) {
		$number_of_pages = $1;
		print "Number of pages: $number_of_pages\n" if $debug;
	}
}

my %params;

if ($url_list_response =~ m{<form name="aspnetForm" method="post" action="List.aspx" id="aspnetForm">(.*?)</form>}msi) {
	my @inputs = $1 =~ m{<input(.*?)>}msig;
	for (@inputs) {
		if( m{name="(.*?)".*?value="(.*?)"} ) {
			$params{$1}=$2;
		} elsif( m{value="(.*?)".*?name="(.*?)"} ) {
			$params{$2}=$1;
		}
	}
	$params{'ctl00$PlaceHolderMain$Img3.x'} = 6;
	$params{'ctl00$PlaceHolderMain$Img3.y'} = 7;
}

get_ads($url_list_response, 0);

for ( sort keys %ADs ) {
	print "$_\n";
}
