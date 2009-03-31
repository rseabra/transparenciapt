#!/usr/bin/perl -w

#       db-load.pl
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

use DBI;
use Net::Netrc;

$mach = Net::Netrc->lookup('dbserver');
$login = $mach->login;
$password = $mach->password;


my $table='ad2';

my $dsn = "DBI:mysql:database=transparenciaptorg;host=127.0.0.1;port=3306";


my $dbh = DBI->connect($dsn, $login, $password);

$drop = "drop table if exists $table;";
$create="CREATE TABLE $table ( id bigint(20) NOT NULL auto_increment, idad bigint(20) default NULL, ent_adjudicante varchar(100) default NULL, nif_ent_adjudicante varchar(9) default NULL, ent_adjudicada varchar(100) default NULL, nif_ent_adjudicada varchar(15) default NULL, objecto longtext, preco double precision default NULL, wtf int(11) default NULL, local varchar(100) default NULL, no_caso longtext, data datetime default NULL, PRIMARY KEY  (id), KEY idx_ent_adjudicante (ent_adjudicante), KEY idx_ent_adjudicada (ent_adjudicada), KEY idx_objecto (objecto(15)), KEY idx_geral (ent_adjudicante,ent_adjudicada,objecto(15)), KEY idx_preco (preco), FULLTEXT KEY ent_adjudicante (ent_adjudicante,ent_adjudicada,objecto)) ENGINE=MyISAM AUTO_INCREMENT=72473 DEFAULT CHARSET=utf8;";

$dbh->do($drop);

$dbh->do($create);

while(<>) {
	chomp;
	my ($idAD, $nif_ent_adj, $ent_adj, $nif_adj, $adj, $objecto, $preco, $inteiro, $local, $no_caso, $data) = split /\t/;
	$query = qq{INSERT INTO $table (idad, ent_adjudicante, nif_ent_adjudicante, ent_adjudicada, nif_ent_adjudicada, objecto, preco, wtf, local, no_caso, data) values ($idAD, '$ent_adj', '$nif_ent_adj', '$adj', '$nif_adj', '$objecto', $preco, $inteiro, '$local', '$no_caso', '$data');};
	#print "$query\n";

	$dbh->do($query);
}

$dbh->disconnect;
