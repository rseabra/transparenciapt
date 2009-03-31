<?php
/*
Template Name: Novidades
*/
?>
<?php get_header(); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="page" id="post-<?php the_ID(); ?>">
                <h2><?php the_title(); ?></h2>
                        <div class="entry">
                                <?php the_content('<p class="serif">Ler o resto desta página &raquo;</p>'); ?>
                                <?php wp_link_pages(array('before' => '<p><strong>Páginas:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                        </div>
                                <?php edit_post_link('Editar', '<div class="postmetadata"><span class="postmetadata-edit">&nbsp;</span>', '</div>'); ?>

<div class="latest_news">
<ul>
 <?php
 global $wp_query, $wp_old_query;
 $wp_old_query = $wp_query;
 query_posts('showposts=4&offset=0');
 if (have_posts()) : while ( have_posts() ) : the_post();
 ?>
    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <?php the_excerpt(); ?></li>
 <?php endwhile; endif; $wp_query = $wp_old_query; ?>
</ul>
</div>

                        </div>
                </div>
          <?php endwhile; endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>

