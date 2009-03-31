<?php
  // register widgetized sidebars
  if (function_exists('register_sidebar')) {
      register_sidebar(array('name' => 'Left Sidebar', 'before_widget' => '<div id="%1$s" class="%2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>', ));
      register_sidebar(array('name' => 'Right Sidebar', 'before_widget' => '<div id="%1$s" class="%2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>', ));
  }
  
  
  
  // function toprint out social bookmarking icons
  function social_bookmarks()
  {
      // remove the next line if you want to show the buttons
      return;      
      
      echo '<span class="socbook">';
      echo '<a href="' . str_replace(array('%title%', '%permalink%', ), array(urlencode($GLOBALS['post']->post_title), urlencode(apply_filters('the_permalink', get_permalink())), ), 'http://reddit.com/submit?title=%title%&amp;url=%permalink%') . '"  target="_blank"><img src="' . get_bloginfo('template_directory') . '/images/reddit_be.gif" alt="Reddit" title="Reddit"/></a>';
      echo '<a href="' . str_replace(array('%title%', '%permalink%', ), array(urlencode($GLOBALS['post']->post_title), urlencode(apply_filters('the_permalink', get_permalink())), ), 'http://www.facebook.com/share.php?title=%title%&amp;u=%permalink%') . '"  target="_blank"><img src="' . get_bloginfo('template_directory') . '/images/facebook_be.gif" alt="Facebook" title="Facebook"/></a>';
      echo '<a href="' . str_replace(array('%title%', '%permalink%', ), array(urlencode($GLOBALS['post']->post_title), urlencode(apply_filters('the_permalink', get_permalink())), ), 'http://www.stumbleupon.com/submit?title=%title%&amp;url=%permalink%') . '"  target="_blank"><img src="' . get_bloginfo('template_directory') . '/images/stumbleupon_be.gif" alt="Stumbleupon" title="Stumbleupon"/></a>';      
      echo '<a href="' . str_replace(array('%title%', '%permalink%', ), array(urlencode($GLOBALS['post']->post_title), urlencode(apply_filters('the_permalink', get_permalink())), ), 'http://digg.com/submit?phase=2&amp;title=%title%&amp;url=%permalink%') . '"  target="_blank"><img src="' . get_bloginfo('template_directory') . '/images/digg_be.gif" alt="Digg" title="Digg"/></a>';      
      echo '<a href="' . str_replace(array('%title%', '%permalink%', ), array(urlencode($GLOBALS['post']->post_title), urlencode(apply_filters('the_permalink', get_permalink())), ), 'http://del.icio.us/post?title=%title%&amp;url=%permalink%') . '"  target="_blank" ><img src="' . get_bloginfo('template_directory') . '/images/del_be.gif" id="del_be" alt="Del.icio.us" title="Del.icio.us"  /></a>';      
      echo '</span>';
  }
  
 
  // this is the multi pages widget
  function wp_widget_multi_pages($args, $number = 1)
  {
      extract($args);
      $options = get_option('widget_multi_pages');
      
      
      $sortby = empty($options[$number]['sortby']) ? 'menu_order' : $options[$number]['sortby'];
      $exclude = empty($options[$number]['exclude']) ? '' : '&exclude=' . $options[$number]['exclude'];
      $headpage = empty($options[$number]['headpage']) ? '' : '&child_of=' . $options[$number]['headpage'];
      $posts = empty($options[$number]['posts']) ? '' : $options[$number]['posts'];
      
      if ($sortby == 'menu_order') {
          $sortby = 'menu_order, post_title';
      }
      $title = $options[$number]['title'];
      
      if ($posts != '') {
          $out = '';
          echo $before_widget . $before_title . $title . $after_title . "<ul>";
          global $post;
          $myposts = get_posts('include=' . $posts);
          foreach ($myposts as $post) {
              setup_postdata($post);
              echo '<li><a href="';
              the_permalink();
              echo '">';
              the_title();
              echo '</a></li>';
          }
          echo "</ul>" . $after_widget;
      } else {
          $out = wp_list_pages('title_li=&echo=0&sort_column=' . $sortby . $exclude . $headpage);
          
          if (!empty($title) && !empty($out)) {
              $out = $before_widget . $before_title . $title . $after_title . "<ul>" . $out . "</ul>" . $after_widget;
          }
          
          
          if (!empty($out)) {
?>
          <?php
              echo $out;
?>
      <?php
          }
      }
  }
  
  function wp_widget_multi_pages_control($number)
  {
      $options = $newoptions = get_option('widget_multi_pages');
      if (!is_array($options))
          $options = $newoptions = array();
      
      if ($_POST["multi-pages-submit-$number"]) {
          $sortby = stripslashes($_POST["multi-pages-sortby-$number"]);
          if (in_array($sortby, array('post_title', 'menu_order', 'ID'))) {
              $newoptions[$number]['sortby'] = $sortby;
          } else {
              $newoptions[$number]['sortby'] = 'menu_order';
          }
          $newoptions[$number]['exclude'] = strip_tags(stripslashes($_POST["multi-pages-exclude-$number"]));
          $newoptions[$number]['headpage'] = strip_tags(stripslashes($_POST["multi-pages-headpage-$number"]));
          $newoptions[$number]['title'] = strip_tags(stripslashes($_POST["multi-pages-title-$number"]));
          $newoptions[$number]['posts'] = strip_tags(stripslashes($_POST["multi-pages-posts-$number"]));
      }
      
      if ($options != $newoptions) {
          $options = $newoptions;
          update_option('widget_multi_pages', $options);
      }
      
      $exclude = attribute_escape($options[$number]['exclude']);
      $headpage = attribute_escape($options[$number]['headpage']);
      $title = attribute_escape($options[$number]['title']);
      $posts = attribute_escape($options[$number]['posts']);
?>
      <p><?php
      _e('Title :');
?> <input type="text" value="<?php
      echo $title;
?>" name="multi-pages-title-<?php
      echo $number;
?>" id="multi-pages-title-<?php
      echo $number;
?>" style="width: 180px;" /><br />
      <small><?php
      _e('Optional.');
?></small></p>
      <p><?php
      _e('Headpage:');
?> <input type="text" value="<?php
      echo $headpage;
?>" name="multi-pages-headpage-<?php
      echo $number;
?>" id="multi-pages-headpage-<?php
      echo $number;
?>" style="width: 180px;" /><br />
      <small><?php
      _e('Page IDs, separated by commas.');
?></small></p>
      <p><?php
      _e('Exclude:');
?> <input type="text" value="<?php
      echo $exclude;
?>" name="multi-pages-exclude-<?php
      echo $number;
?>" id="multi-pages-exclude-<?php
      echo $number;
?>" style="width: 180px;" /><br />
      <small><?php
      _e('Page IDs, separated by commas.');
?></small></p>
      <p><?php
      _e('or<br>Post IDs:');
?> <input type="text" value="<?php
      echo $posts;
?>" name="multi-pages-posts-<?php
      echo $number;
?>" id="multi-pages-posts-<?php
      echo $number;
?>" style="width: 180px;" /><br />
      <small><?php
      _e('Posts IDs, separated by commas.');
?></small></p>  
      <p><?php
      _e('Sort by:');
?>
        <select name="multi-pages-sortby-<?php
      echo $number;
?>" id="multi-pages-sortby-<?php
      echo $number;
?>">
          <option value="post_title"<?php
      selected($options[$number]['sortby'], 'post_title');
?>><?php
      _e('Page title');
?></option>
          <option value="menu_order"<?php
      selected($options[$number]['sortby'], 'menu_order');
?>><?php
      _e('Page order');
?></option>
          <option value="ID"<?php
      selected($options[$number]['sortby'], 'ID');
?>><?php
      _e('Page ID');
?></option>
        </select></p>
      <input type="hidden" id="multi-pages-submit-<?php
      echo $number;
?>" name="multi-pages-submit-<?php
      echo $number;
?>" value="1" />
    <?php
  }
  
  function wp_widget_multi_pages_setup()
  {
      $options = $newoptions = get_option('widget_multi_pages');
      if (isset($_POST['multi-pages-number-submit'])) {
          $number = (int)$_POST['multi-pages-number'];
          if ($number > 9)
              $number = 9;
          if ($number < 1)
              $number = 1;
          $newoptions['number'] = $number;
      }
      if ($options != $newoptions) {
          $options = $newoptions;
          update_option('widget_multi_pages', $options);
          wp_widget_multi_pages_register($options['number']);
      }
  }
  
  function wp_widget_multi_pages_page()
  {
      $options = $newoptions = get_option('widget_multi_pages');
?>
    <div class="wrap">
      <form method="POST">
        <h2><?php
      _e('Multi-pages Widgets');
?></h2>
        <p style="line-height: 30px;"><?php
      _e('How many multi-pages widgets would you like?');
?>
        <select id="multi-pages-number" name="multi-pages-number" value="<?php
      echo $options['number'];
?>">
  <?php
      for ($i = 1; $i < 10; ++$i)
          echo "<option value='$i' " . ($options['number'] == $i ? "selected='selected'" : '') . ">$i</option>";
?>
        </select>
        <span class="submit"><input type="submit" name="multi-pages-number-submit" id="multi-pages-number-submit" value="<?php
      echo attribute_escape(__('Save'));
?>" /></span></p>
      </form>
    </div>
  <?php
  }
  
  function wp_widget_multi_pages_register()
  {
      $options = get_option('widget_multi_pages');
      $number = $options['number'];
      if ($number < 1)
          $number = 1;
      if ($number > 9)
          $number = 9;
      $dims = array('width' => 460, 'height' => 350);
      $class = array('classname' => 'widget_multi_pages');
      for ($i = 1; $i <= 9; $i++) {
          $name = sprintf(__('Multi-pages %d'), $i);
          // Never never never translate an id
          $id = "multi-pages-$i";
          wp_register_sidebar_widget($id, $name, $i <= $number ? 'wp_widget_multi_pages' : /* unregister */ '', $class, $i);
          wp_register_widget_control($id, $name, $i <= $number ? 'wp_widget_multi_pages_control' : /* unregister */ '', $dims, $i);
      }
      add_action('sidebar_admin_setup', 'wp_widget_multi_pages_setup');
      add_action('sidebar_admin_page', 'wp_widget_multi_pages_page');
  }
  
  
  if (function_exists('register_sidebar_widget')) {
      if (function_exists('wp_register_sidebar_widget')) {
          global $wp_register_widget_defaults;
          $wp_register_widget_defaults = false;
          
          wp_widget_multi_pages_register();
          register_sidebar_widget('SEO Archives', 'func_wp_seo_get_archives');
      }
  }
  
  // 
  function make_chunky($ret)
  {
      // pad it with a space
      $ret = ' ' . $ret;
      $ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "$1<a href='$2' rel='nofollow'>$2</a>", $ret);
      $ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "$1<a href='http://$2' rel='nofollow'>$2</a>", $ret);
      //chunk those long urls
      chunk_url($ret);
      $ret = preg_replace("#(\s)([a-z0-9\-_.]+)@([^,< \n\r]+)#i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $ret);
      // Remove our padding..
      $ret = substr($ret, 1);
      return($ret);
  }
  
  
  function chunk_url(&$ret)
  {
      $links = explode('<a', $ret);
      $countlinks = count($links);
      for ($i = 0; $i < $countlinks; $i++) {
          $link = $links[$i];
          
          
          $link = (preg_match('#(.*)(href=")#is', $link)) ? '<a' . $link : $link;
          
          $begin = strpos($link, '>') + 1;
          $end = strpos($link, '<', $begin);
          $length = $end - $begin;
          $urlname = substr($link, $begin, $length);
          
          /**
           * We chunk urls that are longer than 50 characters. Just change
           * '50' to a value that suits your taste. We are not chunking the link
           * text unless if begins with 'http://', 'ftp://', or 'www.'
           */
          $chunked = (strlen($urlname) > 50 && preg_match('#^(http://|ftp://|www\.)#is', $urlname)) ? substr_replace($urlname, '.....', 30, -10) : $urlname;
          $ret = str_replace('>' . $urlname . '<', '>' . $chunked . '<', $ret);
      }
  }
  
  // cut down the long urls in comments
  remove_filter('comment_text', 'make_clickable');
  add_filter('comment_text', 'make_chunky');
  
  
  
  
  function grace_footer()
  {
?> <small>Copyright &copy; <?php
      echo date("Y");
?>  <strong><?php
      bloginfo('name');
?></strong> Some rights reserved, published under the <a href="http://www.gnu.org/copyleft/gpl.html">GNU General Public License</a> <?php
      echo get_current_theme()
?> theme by <a href="http://www.prelovac.com/vladimir/">Vladimir Prelovac</a></small>. <?php
  }
  
  add_action('wp_footer', 'grace_footer');
  
  function grace_scripts()
  {
      if (is_singular())
          wp_enqueue_script('comment-reply');
  }
  
  add_action('wp_print_scripts', 'grace_scripts');
  
  
  // function to display the gravatar
  function show_avatar($comment)
  {
      $size = 40;
      $default = get_bloginfo('stylesheet_directory') . '/images/gravatar.jpg';
      $email = strtolower(trim($comment->comment_author_email));
      // [G | PG | R | X]
      $rating = "G";
      if (function_exists('get_avatar')) {
          echo get_avatar($email, $size, $default);
      } else {
          $grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=
           " . md5($emaill) . "&default=" . urlencode($default) . "&size=" . $size . "&rating=" . $rating;
          echo "<img src='$grav_url'/>";
      }
  }
  
  add_filter('comments_template', 'legacy_comments');
  
  function legacy_comments($file)
  {
      if (!function_exists('wp_list_comments'))          
          $file = TEMPLATEPATH . '/legacy_comments.php';
                
      return $file;
  }
  
  // credit to yoast.com
  function delete_comment_link($id) {
	  if (current_user_can('edit_post')) {
	    global $post;
	    echo '| <a href="'.admin_url("comment.php?action=cdc&c=$id&redirect_to=/".$post->post_name."/").'">del</a> ';
	    echo '| <a href="'.admin_url("comment.php?action=cdc&dt=spam&c=$id&redirect_to=/".$post->post_name."/").'">spam</a>';
	  }
	}
?>
