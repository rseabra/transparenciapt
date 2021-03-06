<div class="search-form">

<?php
include(TEMPLATEPATH . '/pesquisa_functions.php');

 	$sort = array('preco DESC', 'preco ASC', 'data DESC', 'data ASC');

	$default_search_val = "buscar por...";

	if ( $_GET['search_str'] != "") {
		#$search_val = $wpdb->escape(ereg_replace("[\\\<\>\|\;]","",$_GET['search_str']));
		$search_val = preg_replace("/[^a-záàãâéèêíìóòôúûç0-9\:\+\-\"\*\(\)[:space:]]+/i","",$_GET['search_str']);
	} else {
		$search_val = $default_search_val;
	}

	if ( $_GET['sort'] == "") {
		$sort_val=0;
		$preco_desc='checked="checked"';
	} else {
		$sort_val = $wpdb->escape($_GET['sort']);
		if(is_numeric($sort_val)) {
			if($sort_val==1) {
				$preco_asc='checked="checked"';
			} elseif($sort_val==2) {
				$data_asc='checked="checked"';
			} elseif($sort_val==3) {
				$data_desc='checked="checked"';
			} else {
				$preco_desc='checked="checked"';
			}
		} else {
			$sort_val=0;
			$preco_asc='';
			$preco_desc='checked="checked"';
		}
	}
?>
	<strong>Pesquise nos Ajustes Directos</strong><br />Exemplos: "nome de entidade" | <a href="/?search_str=Vortal">Vortal</a> | <a href="/?search_str=software">software</a> | <a href="/?search_str=computador">computador</a> | <a href="/?search_str=viatura">viatura</a> | <a href="/?search_str=obra">obra</a>, saiba mais nas <a href="/?page_id=42">Perguntas Frequentes</a> <br />
	<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/?">
	<div class="search-box">
		<input size="60" type="text" value='<?php echo $search_val; ?>' name="search_str" id="s" onblur="if (this.value == '')
                {this.value = '<?php echo $search_val; ?>';}"
                onfocus="if (this.value == '<?php echo $default_search_val; ?>')
                {this.value = '';}" />
		<input type="submit" id="searchsubmit" value="Pesquisar..."/><br />
	</div><!--
		<input type="radio" id="sort" name="sort" value="0" <?php echo $preco_desc; ?> > Montante decrescente <br />
		<input type="radio" id="sort" name="sort" value="1" <?php echo $preco_asc; ?> > Montante crescente -->
	</form>
</div>


<?php	if ( $search_val != "" && $search_val != $default_search_val && strlen($search_val) >= 3 ) {
		echo '<div id="search_results">';

		$order = $sort[$sort_val % 4];

		#$query = "SELECT idad, ent_adjudicante, nif_ent_adjudicante, ent_adjudicada, nif_ent_adjudicada, objecto, preco FROM ad WHERE lower(objecto) LIKE lower('%$search_val%') ORDER BY ".$order.";";
		#$query = strtolower("select idad, ent_adjudicante, nif_ent_adjudicante, ent_adjudicada, nif_ent_adjudicada, objecto, preco, data from ad where lower(objecto) like '%$search_val%' or lower(ent_adjudicante) like '%$search_val%' or lower(ent_adjudicada) like '%$search_val%' order by ".$order.";");

		if( preg_match_all("/nif:([0-9]{9})/", $search_val, $matches) ) {
			$nifs = $matches[1];
			for($i=0; $i<count($nifs) && $i < 4;$i++) {
				if ($where == "") {
					$where="nif_ent_adjudicante = '".$nifs[$i]."' or nif_ent_adjudicada = '".$nifs[$i]."'";
				} else {
					$where="$where or nif_ent_adjudicante = '".$nifs[$i]."' or nif_ent_adjudicada = '".$nifs[$i]."'";
				}
			}
			#echo $where;
			$query = sprintf("select idad, ent_adjudicante, nif_ent_adjudicante, ent_adjudicada, nif_ent_adjudicada, objecto, preco, data from ad where $where order by %s;", $order);
			#print $query;
		} else {
			$query = sprintf("select idad, ent_adjudicante, nif_ent_adjudicante, ent_adjudicada, nif_ent_adjudicada, objecto, preco, data from ad where match (objecto, ent_adjudicante, ent_adjudicada) against ('%s' in boolean mode) order by %s;", $search_val, $order);
		}
		$wpdb->hide_errors();
		$result = $wpdb->get_results($query, ARRAY_A);
		$wpdb->show_errors();

		mostra_ads(1, $search_val, $result);
		echo "</div>";
	}
?>
