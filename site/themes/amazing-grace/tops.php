<?php
/*
Template Name: Estatisticas
*/
?>

<?php get_header(); ?>
<div id="content">
<?php
	include (TEMPLATEPATH . '/pesquisa_functions.php');

# on second thought... e' melhor que isto seja esta'tico. na~o ha' actualizac,a~o em directo e na~o...

	# create table ad_stats ( id int NOT NULL auto_increment PRIMARY KEY, data datetime not null, numero_registos int, desvio_padrao float, media float, mediana float, maximo float, minimo float, total float, adjudicantes int, adjudicadas int );
	# create index idx_ad_stats_date on ad_stats (data);

	# select count(id) as numero_registos, std(preco) as desvio_padrao, avg(preco) as media from ad2;
	# select max(preco) as maximo, min(preco) as minimo, sum(preco) as total, count(distinct nif_ent_adjudicante) as adjudicantes,count(distinct nif_ent_adjudicada) as adjudicadas from ad2;
	# CREATE TEMPORARY TABLE tmp ( n INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, value float NOT NULL );
	# INSERT INTO tmp (value) SELECT preco FROM ad2 ORDER BY 1;
	# SELECT @count := COUNT(*) FROM tmp;
	# SELECT DISTINCT value FROM tmp WHERE n IN (CEIL((@count+1)/2)); 
	# DROP TEMPORARY TABLE tmp;
	# insert into ad_stats (data, numero_registos, desvio_padrao, media, mediana, maximo, minimo, total, adjudicantes, adjudicadas) values ( '2009-01-17 00:00:00', 19021, 191229.5292549, 23879.645264904, 6705.3, 10080000, 0.079999998211861, 461736820.84218, 1074, 7895);
	# insert into ad_stats (data, numero_registos, desvio_padrao, media, mediana, maximo, minimo, total, adjudicantes, adjudicadas) values ( '2009-01-26 08:00:00', 19336, 70150.332261895, 18304.314602193, 6740.6, 4653239, 0.079999998211861, 348166368.04832, 1094, 7859);
	# insert into ad_stats (data, numero_registos, desvio_padrao, media, mediana, maximo, minimo, total, adjudicantes, adjudicadas) values ( '2009-01-27 14:37:00', 21375, 63395.90077233, 17881.885361635, 6640, 4653239, 0.079999998211861, 382225299.60495, 1140, 8458);
	# insert into ad_stats (data, numero_registos, desvio_padrao, media, mediana, maximo, minimo, total, adjudicantes, adjudicadas) values ( '2009-01-27 21:41:00', 21516, 63154.602111463, 17770.65866057, 6590.63, 4653239, 0.079999998211861, 382353491.74081, 1142, 8478);
	# insert into ad_stats (data,                   numero_registos, desvio_padrao,   media,           mediana, maximo,  minimo,            total,           adjudicantes, adjudicadas) values                                               ( '2009-02-27 19:14:00', 21576,           57773.454415219, 17432.466377435, 6342,    3340000, 0.079999998211861, 376122894.55953, 1238,         8566);
	# insert into ad_stats (data,                   numero_registos, desvio_padrao,   media,           mediana, maximo,  minimo,            total,           adjudicantes, adjudicadas) values                                               ( '2009-03-04 20:00:00', 30687,           89169.405853894, 18289.133145272, 6375,    9986795, 0.079999998211861, 561238628.82897, 1371,         10651);
	# insert into ad_stats (data,                   numero_registos, desvio_padrao,   media,           mediana, maximo,     minimo,            total,           adjudicantes, adjudicadas) values                                            ( '2009-03-16 17:34:00', 34254,           86789.292027678, 18469.780700356, 6429.5,  9986794.93, 0.08,              632663868.11,    1426,         11451);


	$wpdb->hide_errors();
	$stats = $wpdb->get_results("select * from ad_stats order by data desc limit 1;", ARRAY_A);
	$wpdb->show_errors();

	$data=$stats[0]['data'];
	$numero_registos=$stats[0]['numero_registos'];
	$desvio_padrao=$stats[0]['desvio_padrao'];
	$media=$stats[0]['media'];
	$mediana=$stats[0]['mediana'];
	$maximo=$stats[0]['maximo'];
	$minimo=$stats[0]['minimo'];
	$total=$stats[0]['total'];
	$adjudicantes=$stats[0]['adjudicantes'];
	$adjudicadas=$stats[0]['adjudicadas'];

	# depois de actualizar estes valores...
	# drop table ad; alter table ad2 rename ad;

	#$wpdb->hide_errors();
	#$result = $wpdb->get_results($query, ARRAY_A);
	#$result2 = $wpdb->get_results($query2, ARRAY_A);
	#$wpdb->show_errors();

	echo '<div><h2>Indicadores gerais</h2>';
	echo "Os indicadores seguites foram obtidos a partir da BD, <strong>actualizada em $data</strong>.<br />As actualiza&ccedil;&otilde;es desta BD est&atilde;o em <a href='/files/dumps/'>ficheiros de <em>Comma Separated Values</em> dispon&iacute;veis aqui</a>.";
	echo '<table class="search_results_table"><tr><th>Indicador</th><th>Valor</th></tr>';

	#echo '<tr class="search_results_odd"><td>N&uacute;mero de Registos</td><td class="montante">'.$result[0]['numero_registos'].'</td></tr>';
	#echo '<tr class="search_results_even"><td>N&uacute;mero de Entidades Adjudicantes</td><td class="montante">'.$result2[0]['adjudicantes'].'</td></tr>';
	#echo '<tr class="search_results_odd"><td>N&uacute;mero de Entidades Adjudicadas</td><td class="montante">'.$result2[0]['adjudicadas'].'</td></tr>';
	#echo '<tr class="search_results_odd"><td>Montante total</td><td class="montante">'.(number_format($result2[0]['total'],2,",",".")).'&nbsp;&euro;</td></tr>';
	#echo '<tr class="search_results_odd"><td>Montante m&aacute;ximo</td><td class="montante">'.(number_format($result2[0]['maximo'],2,",",".")).'&nbsp;&euro;</td></tr>';
	#echo '<tr class="search_results_odd"><td>Montante m&iacute;nimo</td><td class="montante">'.(number_format($result2[0]['minimo'],2,",",".")).'&nbsp;&euro;</td></tr>';
	#echo '<tr class="search_results_odd"><td>Montante m&eacute;dio</td><td class="montante">'.(number_format($result[0]['media'],2,",",".")).'&nbsp;&euro;</td></tr>';
	#echo '<tr class="search_results_even"><td>Desvio Padr&atilde;o</td><td class="montante">'.(number_format($result[0]['desvio_padrao'],2,",",".")).'&nbsp;&euro;</td></tr>';

	echo '<tr class="search_results_odd"><td>N&uacute;mero de Registos</td><td class="montante">'.$numero_registos.'</td></tr>';
	echo '<tr class="search_results_even"><td>N&uacute;mero de Entidades Adjudicantes</td><td class="montante">'.$adjudicantes.'</td></tr>';
	echo '<tr class="search_results_odd"><td>N&uacute;mero de Entidades Adjudicadas</td><td class="montante">'.$adjudicadas.'</td></tr>';
	echo '<tr class="search_results_even"><td>Montante total</td><td class="montante">'.(number_format($total,2,",",".")).'&nbsp;&euro;</td></tr>';
	echo '<tr class="search_results_odd"><td>Montante m&aacute;ximo</td><td class="montante">'.(number_format($maximo,2,",",".")).'&nbsp;&euro;</td></tr>';
	echo '<tr class="search_results_even"><td>Montante m&iacute;nimo</td><td class="montante">'.(number_format($minimo,2,",",".")).'&nbsp;&euro;</td></tr>';
	echo '<tr class="search_results_odd"><td>Mediana</td><td class="montante">'.(number_format($mediana,2,",",".")).'&nbsp;&euro;</td></tr>';
	echo '<tr class="search_results_even"><td>Montante m&eacute;dio</td><td class="montante">'.(number_format($media,2,",",".")).'&nbsp;&euro;</td></tr>';
	echo '<tr class="search_results_odd"><td>Desvio Padr&atilde;o</td><td class="montante">'.(number_format($desvio_padrao,2,",",".")).'&nbsp;&euro;</td></tr>';

	echo '</table>';

	echo '<h2>Top 10 das entidades adjudicadas</h2>';
	$query = "select ent_adjudicada as ent, nif_ent_adjudicada as nif, sum(preco) as total from ad group by nif_ent_adjudicada order by total desc limit 10;";
	$wpdb->hide_errors();
	$result = $wpdb->get_results($query, ARRAY_A);
	$wpdb->show_errors();

	mostra_entidades(1, $result);

	echo '<h2>Top 10 das entidades adjudicantes</h2>';
	$query = "select ent_adjudicante as ent, nif_ent_adjudicante as nif, sum(preco) as total from ad group by nif_ent_adjudicante order by total desc limit 10;";
	$wpdb->hide_errors();
	$result = $wpdb->get_results($query, ARRAY_A);
	$wpdb->show_errors();

	mostra_entidades(0, $result);

	echo '<h2>Top 10 dos montantes mais elevados</h2>';
	$query = "select * from ad order by preco desc limit 10;";
	$wpdb->hide_errors();
	$result = $wpdb->get_results($query, ARRAY_A);
	$wpdb->show_errors();

	mostra_ads(0, $search_val, $result);

	echo '<h2>Top 10 dos montantes mais baixos</h2>';
	$query = "select * from ad order by preco asc limit 10;";
	$wpdb->hide_errors();
	$result = $wpdb->get_results($query, ARRAY_A);
	$wpdb->show_errors();

	mostra_ads(0, $search_val, $result);
	echo "</div>";
?>

</div>

<?php get_footer(); ?>
