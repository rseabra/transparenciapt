<?php // Do not delete these lines
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

    if (!empty($post->post_password)) { // if there's a password
       if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
		?>
			
		<p class="nocomments">This post is password protected. Enter the password to view comments.<p>

	<?php

		return;
	}
}
	/* This variable is for alternating comment background */
	$oddcomment = 'alt';
?>
		
<?php if ( $comments ) : ?>
	<h3 id="comments" class="comment_headings"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">
		<?php foreach ($comments as $comment) : ?>    	
      <?php $comment_type = get_comment_type(); ?>
			<?php if($comment_type == 'comment') { ?>

			<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">
				
				<?php show_avatar($comment) ?>
			
				<div class="vcard"><cite><?php comment_author_link() ?></cite><br />
					<small><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); delete_comment_link(get_comment_ID()); ?></small>
				</div>
			
			<?php if ($comment->comment_approved == '0') : ?>
				<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>
			
			<?php comment_text() ?>
			
			</li>

			<?php /* Changes every other comment to a different class */	
				if ($oddcomment == 'alt') $oddcomment = '';
				else $oddcomment = 'alt';
			?>

	<?php } /* End of is_comment statement */ ?>
    
	<?php endforeach; /* end for each comment */ ?>

	</ol>


	<?php 
		// check for trackbacks/pingbacks
	  $tracks=0;
	  foreach ($comments as $comment) : 
	    $comment_type = get_comment_type(); 
	    if($comment_type != 'comment')  {
	      $tracks=1;
	      break;
	    }
	  endforeach; 
	?>

	<?php if ($tracks) : ?>
	
	<h3 class="comment_headings">Trackbacks/Pingbacks</h3>
		<ol>
		<?php foreach ($comments as $comment) : ?>
			<?php $comment_type = get_comment_type(); ?>
			<?php if($comment_type != 'comment') { ?>
			<li><?php comment_author_link() ?></li>
			<?php } ?>
		<?php endforeach; ?>
		</ol>
	<?php endif; ?>
	
<?php else : // this is displayed if there are no comments so far ?>
	
	 <?php if ('open' == $post->comment_status) : ?> 		
			
		<?php else : // comments are closed ?>		
	
		<p class="nocomments"></p>
			
		<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

	<h3 id="respond" class="comment_headings">Leave a Reply</h3>
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>
	
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	
		<?php if ( $user_ID ) : ?>
		
			<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>
		
		<?php else : ?>
			
			<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
			<label for="author"><small>Name<?php if ($req) echo " (required)"; ?></small></label></p>
			
			<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
			<label for="email"><small>Email (will not be published) <?php if ($req) echo "(required)"; ?></small></label></p>
			
			<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			<label for="url"><small>Website</small></label></p>
				
		<?php endif; ?>
		
		<p><small>You can use these tags: <?php echo allowed_tags(); ?></small></p>
		<p><textarea name="comment" id="comment" cols="100%" rows="15" tabindex="4"></textarea></p>
		
		<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		</p>
		 
		<?php do_action('comment_form', $post->ID); ?>	
	
	</form>


<?php endif; // If registration required and not logged in ?> 
 


<?php endif;  ?>
