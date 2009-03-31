<?php get_header(); ?>
<div id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); $loopcounter++; ?>

		<div <?php if (function_exists('post_class')) post_class(); ?>>

			<div class="entry entry-<?php echo $postCount ;?>">
			
				<div class="entrytitle_wrap">
					<?php if (!is_page()) : ?>
						<div class="entrydate">
							<div class="dateMonth">
								<?php the_time('M');?>
							</div>
							<div class="dateDay">
								<?php the_time('j'); ?>
							</div>
						</div>
					<?php endif; ?>
				
					<div class="entrytitle">
					<?php if ($loopcounter==1):?>  
						<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1> 
					<?php else : ?>
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2> 
					<?php endif; ?>
					</div>
			
					<?php if (!is_singular()): ?>
						<div class="endate"><?php the_author(); ?> on <?php the_time('F jS, Y'); ?></div>
					<?php endif; ?>	
				</div>
			
			
				<div class="entrybody">	
					<?php if (is_archive() || is_search()) : ?>	
						<?php the_excerpt(); _e('<p><a href="'.get_permalink().'">Continue reading about '); the_title(); _e('</a></p>');  ?>
					<?php else : ?>
						<?php the_content('Read the rest of this entry &raquo;');   ?>
						<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
					<?php endif; ?>			
				</div>
			
				<div class="entrymeta">	
					<div class="postinfo"> 
				
						<?php if ($loopcounter==1) social_bookmarks(); ?>	
						<?php if (is_single()): ?>
						 <span class="postedby">Posted by <?php the_author() ?></span>
						<?php endif; ?>
						
						<?php if (!is_page()): ?>
							<span class="filedto"><?php the_category(', ') ?> </span>
						<?php endif; ?>
						
						<?php if (!is_singular()): ?>
							<span class="commentslink"><?php comments_popup_link(_e('No comments yet.'), '1 coment&aacute;rio', '% coment&aacute;rios');?> &#187;</span>  					
						<?php else: ?>
							<span class="rss">Subscribe to <a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Subscribe to RSS feed'); ?>" ><?php _e('<abbr title="Subscribe to RSS Feed">RSS</abbr>'); ?></a> feed</span>
						<?php endif; ?>
				
						<?php edit_post_link('Editar', ' ', ''); ?>

						<?php the_tags('Temas', ', '); ?>
				
					</div>	
				</div>
			
			                    
				<?php if ($loopcounter == 1 && !is_singular()) { include (TEMPLATEPATH . '/ad_middle.php'); } ?>                 
			
			</div>	
			
			<?php if (is_singular()): ?>
				<div class="commentsblock">
					<?php comments_template(); ?>
				</div>
			<?php endif; ?>
		
	</div>
	
	<?php endwhile; ?>
	
	<?php if (!is_singular()): ?>         
		<div id="nav-global" class="navigation">
			<div class="nav-previous">
			<?php 
				next_posts_link('&laquo; Previous entries');
				echo '&nbsp;';
				previous_posts_link('Next entries &raquo;');
			?>
			</div>
		</div>
		
	<?php endif; ?>
		
	<?php else : ?>
	
		<h2>Not Found</h2>
		<div class="entrybody">Sorry, but you are looking for something that isn't here.</div>
	<?php endif; ?>
	
</div>

<?php get_footer(); ?>
