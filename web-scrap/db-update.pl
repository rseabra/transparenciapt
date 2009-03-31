#!/usr/bin/perl -w

use DBI;
use Net::Netrc;

$mach = Net::Netrc->lookup('dbserver');
$login = $mach->login;
$password = $mach->password;

my $table='ad';

my $dsn = "DBI:mysql:database=transparenciaptorg;host=127.0.0.1;port=3306";

my $dbh = DBI->connect($dsn, $login, $password);

while(<>) {
	chomp;
	my ($idAD, $nif_ent_adj, $ent_adj, $nif_adj, $adj, $objecto, $preco, $inteiro, $local, $no_caso, $data) = split /\t/;
	$query = qq{INSERT INTO $table (idad, ent_adjudicante, nif_ent_adjudicante, ent_adjudicada, nif_ent_adjudicada, objecto, preco, wtf, local, no_caso, data) values ($idAD, '$ent_adj', '$nif_ent_adj', '$adj', '$nif_adj', '$objecto', $preco, $inteiro, '$local', '$no_caso', '$data');};
	#print "$query\n";

	$dbh->do($query);
}

$dbh->disconnect;
