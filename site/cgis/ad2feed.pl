#!/usr/bin/perl -wT


use strict;
use DBI;
use CGI qw/:standard/;
use XML::RSS;

my $q = new CGI;

my $search_val=$q->param('search_str');

my $limit = 20;

$search_val =~ s/[^a-záàãâéèêíìóòôúûç0-9\:\+\-\"\*\(\)[:space:]]+//msgi;

my $table='ad';

my $dsn = "DBI:mysql:database=transparenciaptorg;host=127.0.0.1;port=3306";
my $dbh = DBI->connect($dsn, 'user', 'password');
my $query= "select * from ad where match (objecto, ent_adjudicante, ent_adjudicada) against ('$search_val' in boolean mode) order by data DESC limit $limit;";
my $sth = $dbh->prepare($query);
$sth->execute;

my $rss = new XML::RSS (version => '2.0');

$rss->channel(
	title		=> "Transpar\&ecirc;ncia na AP: '$search_val'",
	link		=> 'http://transparencia-pt.org/',
	language	=> 'pt',
	description	=> "Subscri&ccedil;&atilde;o de Ajustes Directos sobre '$search_val'",
	copyright	=> 'Informa&ccedil;&atilde;o P&uacute;blica',
);


my ($id, $idad, $ent_adjudicante, $nif_ent_adjudicante, $ent_adjudicada, $nif_ent_adjudicada, $objecto, $preco, $wtf, $local, $no_caso, $data);

$sth->bind_columns(undef, \$id, \$idad, \$ent_adjudicante, \$nif_ent_adjudicante, \$ent_adjudicada, \$nif_ent_adjudicada, \$objecto, \$preco, \$wtf, \$local, \$no_caso, \$data);

while($sth->fetch) {
	#print $idad, $ent_adjudicante, $nif_ent_adjudicante, $ent_adjudicada, $nif_ent_adjudicada, $objecto, $preco, $data, $local, $no_caso;
	$data =~ s/\s+\d{2}.*$//;
	$rss->add_item(
		title		=> "$ent_adjudicante -> $ent_adjudicada: $preco",
		permaLink	=> "http://www.base.gov.pt/_layouts/ccp/AjusteDirecto/Detail.aspx?idAjusteDirecto=$idad",
		description	=> "<a href='http://www.base.gov.pt/_layouts/ccp/AjusteDirecto/Detail.aspx?idAjusteDirecto=$idad'>Ajuste Directo $idad</a> registado em $data, de <strong>$ent_adjudicante</strong> para <a href='http://publicacoes.mj.pt/pt/Pesquisa.asp?sFirma=&dfDistrito=&dfConcelho=&dInicial=&dFinal=&iTipo=0&sCAPTCHA=&pesquisar=Pesquisar&dfConcelhoDesc=&iNIPC=$nif_ent_adjudicada'>$ent_adjudicada</a>, no montante de $preco &euro; para:<br /><blockquote><em>$objecto</em></blockquote>",
	)
}

$dbh->disconnect;

print "Content-type: application/rss+xml\n\n";
my $text =  $rss->as_string;
$text =~ s/\<\?xml version="1.0" encoding="UTF-8"\?\>//msgi;
print $text;
