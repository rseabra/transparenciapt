<?php
/*
Template Name: Investigar
*/
?>

<?php get_header(); ?>
<div id="content">
<?php
	include (TEMPLATEPATH . '/pesquisa_functions.php');

	$query = "select distinct ad.idad as idad, ad.ent_adjudicante as ent_adjudicante, ad.nif_ent_adjudicante as nif_ent_adjudicante, ad.ent_adjudicada as ent_adjudicada, ad.nif_ent_adjudicada as nif_ent_adjudicada, ad.objecto as objecto, ad.preco as preco, ad.data as data from ad, ad as other where ad.idad <> other.idad and ad.nif_ent_adjudicante = other.nif_ent_adjudicante and ad.nif_ent_adjudicada = other.nif_ent_adjudicada and ad.objecto = other.objecto and ad.preco > 20000 order by ad.nif_ent_adjudicante, ad.nif_ent_adjudicada, ad.objecto;";

		#$wpdb->hide_errors();
		$result = $wpdb->get_results($query);
		#$wpdb->show_errors();

		mostra_ads_investiga(1, "", $result);

?>




