<?php get_header(); ?>

	<div id="content">

<h2>Oops!</h2>
	<p>The page you're looking for can't be found. But wait, try this:</p>
	<ul>
        <li>You could visit the <a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a> home page.</li>		
        <li>You can search the site using the search box to the right.</li>	
	</ul>

Or you check out the recent articles:
	<ul>
	<?php
	query_posts('posts_per_page=10');
	if (have_posts()) : while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" title="Permalink for : <?php the_title(); ?>"><?php the_title(); ?></a>
	<?php endwhile; endif; ?>
	</ul>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
