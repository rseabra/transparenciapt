#!/usr/bin/perl -wT

#       ad2csv.pl
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


use strict;
use DBI;
use CGI qw/:standard/;

my $q = new CGI;

my $search_val=$q->param('search_str');

$search_val =~ s/[^a-záàãâéèêíìóòôúûç0-9\:\+\-\"\*\(\)[:space:]]+//msgi;

my $table='ad';

my $dsn = "DBI:mysql:database=transparenciaptorg;host=127.0.0.1;port=3306";

my $dbh = DBI->connect($dsn, 'user', 'password');

my $query= "select * from ad where match (objecto, ent_adjudicante, ent_adjudicada) against ('$search_val' in boolean mode) order by data;";

my $sth = $dbh->prepare($query);
$sth->execute;

$, = "\t";
$\ = "\n";

print "Content-type: text/csv\n\n";

my ($id, $idad, $ent_adjudicante, $nif_ent_adjudicante, $ent_adjudicada, $nif_ent_adjudicada, $objecto, $preco, $wtf, $local, $no_caso, $data);

$sth->bind_columns(undef, \$id, \$idad, \$ent_adjudicante, \$nif_ent_adjudicante, \$ent_adjudicada, \$nif_ent_adjudicada, \$objecto, \$preco, \$wtf, \$local, \$no_caso, \$data);


print "IDAD", "Entidade Adjudicante", "NIF da Entidade Adjudicante", "Entidade Adjudicada", "NIF da Entidade Adjudicada", "Objecto", "Montante", "Data", "Local", "No Caso dos ADs";

while($sth->fetch) {
	print $idad, $ent_adjudicante, $nif_ent_adjudicante, $ent_adjudicada, $nif_ent_adjudicada, $objecto, $preco, $data, $local, $no_caso;
}

$dbh->disconnect;
