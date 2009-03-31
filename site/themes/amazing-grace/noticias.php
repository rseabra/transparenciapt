<div class="latest_news">
<ul>
 <?php
 global $wp_query, $wp_old_query;
 $wp_old_query = $wp_query;
 query_posts('showposts=2&offset=0&cat=3');
 if (have_posts()) {
	echo '<h3>&Uacute;ltimas <a href="">novidades</a>...</h3>';
 
 	while ( have_posts() ) {
		the_post();
    		echo '<li><a href="'.(the_permalink()).'">'.(the_title()).'</a> '.(the_excerpt()).'</li>';
 	}
 }
 $wp_query = $wp_old_query;
 
?>
</ul>
</div>
