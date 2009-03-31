#!/usr/bin/perl -w

#       parse-doc.pl
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


use HTML::Entities;
use Encode;
use Encode::Detect;

binmode(STDOUT, ':encoding(UTF-8)');

my $repo = $ARGV[0];
my $idad = $ARGV[1];

my $utf8 = find_encoding("utf8");
my $latin1 = find_encoding("iso-8859-1");
my $latin15 = find_encoding("iso-8859-15");
my $windows = find_encoding("cp1252");

undef $/;

open REPO,$repo;
$ad = <REPO>;
close REPO;

$ad =~ s/\r//g;

# Get AD date
if ( $ad =~ /title="Data de registo">(.*?)</ ) {
	$data = ad_filter_date($1);
} else {
	print STDERR "Error on AD date\n";
	exit 1;
}

# Get Adjudicante
if ( $ad =~ /<table class="inci_grdVw" cellspacing="0" rules="all" border="1" id="ctl00_PlaceHolderMain_gvEntidadesAdjudicantes".*?>(.*?)<\/table>/sm ) {
	my $table = $1;

	
	if ( $table =~ /<td align="center" valign="middle">(.*?)<\/td><td>(.*?)<\/td>/sm ) {
		$nif_ent_adjudicante = ad_filter_int($1);
		$ent_adjudicante = ad_filter($2);
	} else {
		print STDERR "Error getting Adjudicante (inside)\n";
		exit 2;
	}

} else {
	print STDERR "Error getting Adjudicante (outside)\n";
	exit 2;
}

# Get Adjudicada
if ( $ad =~ /<table class="inci_grdVw" cellspacing="0" rules="all" border="1" id="ctl00_PlaceHolderMain_gvEntidadesAdjudicatarias".*?>(.*?)<\/table>/sm ) {
	my $table = $1;

	if ( $table =~ /<td align="center" valign="middle">(.*?)<\/td><td>(.*?)<\/td>/sm ) {
		$nif_ent_adjudicada = ad_filter_int($1);
		$ent_adjudicada = ad_filter($2);
	} else {
		print STDERR "Error getting Adjudicada (inside)\n";
		exit 3;
	}

} else {
	print STDERR "Error getting Adjudicante (outside)\n";
	exit 3;
}

# Get Objecto
if ( $ad =~ /title="Objecto do contrato\(descrição sumária\):">(.*?)</sm ) {
	$objecto = ad_filter($1);
} else {
	print STDERR "Error getting Objecto\n";
	exit 4;
}

# Get Preco
if ( $ad =~ /title="Preço do contrato.">(.*?)</sm ) {
	$preco = ad_filter_float($1);
} else {
	print STDERR "Error getting Preco\n";
	exit 5;
}

# Get Prazo
if ( $ad =~ /title="Prazo de execução">(.*?)</sm ) {
	$prazo = ad_filter($1);
} else {
	print STDERR "Error getting Prazo\n";
	exit 6;
}

# Get Local
if ( $ad =~ /title="Local de execução">(.*?)</sm ) {
	$local = ad_filter($1);
} else {
	print STDERR "Error getting Local\n";
	exit 7;
}

# Get NoCasoDosADs
if ( $ad =~ /title="No caso dos ajustes directos: critério material de escolha do tipo de procedimento \(se aplicável\).">(.*?)</sm ) {
	$nocasodosads = ad_filter($1);
} else {
	print STDERR "Error getting NoCaso\n";
	exit 8;
}

printf "%d	%d	%s	%d	%s	%s	%f	%s	%s	%s	%s\n", $idad, $nif_ent_adjudicante, $ent_adjudicante, $nif_ent_adjudicada, $ent_adjudicada, $objecto, $preco, $prazo, $local, $nocasodosads, $data;

exit 0 ;

sub ad_filter {
	my ($val) = @_;

	$val =~ s/–/-/gims;
	$val =~ s/[\t\r\n]/ /gims;
	return $val if $val =~ /^[[:space:]]*$/;
	$val = decode("Detect", $val);
	$val =~ s/\&amp;amp;/\&amp;/gims;
	decode_entities($val);
	$val = cp1252_fixup($val);
	Encode::_utf8_on($val);

	$val =~ s/'/''/gims;
	#$val =~ s/[^a-záàãâéèêíìîóòõôúùûç0-9\+\-\"\*\(\)ºª\?\!\;\:\,\.[:space:]\&']+//gims;
	$val =~ s/[^[:alnum:]\+\-\"\*\(\)ºª\?\!\;\:\,\.[:space:]\&']+//gims;
	return $val;
}

sub cp1252_fixup {
# replaces the additional WinLatin-1 chars in the 0x80 - 0x9F range
# with the corresponding Unicode character
	my $str = shift;
	$str =~ tr/\x80-\x9f/\x{20AC}\x{FFFD}\x{201A}\x{192}\x{201E}\x{2026}\x{2020}\x{2021}\x{2C6}\x{2030}\x{160}\x{2039}\x{152}\x{FFFD}\x{17D}\x{FFFD}\x{FFFD}\x{2018}\x{2019}\x{201C}\x{201D}\x{2022}\x{2013}\x{2014}\x{2DC}\x{2122}\x{161}\x{203A}\x{153}\x{FFFD}\x{17E}\x{178}/;
	return($str);
}

sub ad_filter_int {
	my ($val) = @_;

	$val =~ s/[^0-9]+//g;
	return $val;
}

sub ad_filter_float {
	my ($val) = @_;

	$val =~ s/[^0-9\,]+//g;
	$val =~ s/\,/\./g;
	return $val;
}

sub ad_filter_date {
	my ($val) = @_;

	$val =~ s/^.*?((\d{2})\-(\d{2})\-(\d{4})).*/$4\-$3\-$2/;
	return $val;
}
