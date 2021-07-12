<?php
/*
Template Name: toss
Template Post Type: post, page, event
*/
?>
<?php get_header(); ?>

<script>
jQuery(document).ready(function () {
 jQuery('.js-category-button').on('click', function(e){
  e.preventDefault();
  var catID = jQuery(this).data('slug');
  var ajaxurl = 'http://localhost/wordpress/wp-admin/admin-ajax.php';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        crossDomain : true,
        dataType: 'html',
        data: {"action": "load-filter", cat: catID },
        beforeSend: function () {
            jQuery(".the-news").html('<p></p>')
        },
        success: function(response) {
            jQuery(".the-news").append(response);
            return false;
        }
    });
})

}); 
</script>

<div class="form-check">
	<?php

        $cat_args = array(
          'orderby'     => 'name',
          'order'       => 'ASC',
          'hide_empty'  => 1
        );


        $cats = get_categories($cat_args);

   foreach($cats as $cat):?>
  <input data-slug="<?= $cat->term_id;?>" class="form-check-input js-category-button" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
   <?=$cat->name;?>
  </label>
<?php endforeach;?>
</div>

<div class="dropdown">

  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropdown button
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  	<?php

        $cat_args = array(
          'orderby'     => 'name',
          'order'       => 'ASC',
          'hide_empty'  => 1
        );


        $cats = get_categories($cat_args);

        foreach($cats as $cat){
    echo '<a href="#" data-slug="' . $cat->term_id . '" class="dropdown-item js-category-button">' . $cat->name . '</a>';
        }
        ?>
  </div>
</div>

<ul>
      <?php

        $cat_args = array(
          'orderby'     => 'name',
          'order'       => 'ASC',
          'hide_empty'  => 1
        );


        $cats = get_categories($cat_args);

        foreach($cats as $cat){
          echo '<li><a href="#" data-slug="' . $cat->term_id . '" class="js-category-button">' . $cat->name . '</a></li>';
        }

      ?>
      <div class="the-news">

    </div>
</ul>     
<?php get_footer(); ?>


/**
 * Ajax filter setup
 */
add_action( 'wp_ajax_nopriv_load-filter', 'prefix_load_cat_posts' );
    add_action( 'wp_ajax_load-filter', 'prefix_load_cat_posts' );

        function prefix_load_cat_posts () {

          global $post;

          $cat_id = $_POST[ 'cat' ];

          $args = array (
            'cat' => $cat_id,
            'posts_per_page' => -1,
            'order' => 'ASC'
          );


          $cat_query = new WP_Query($args);

          if($cat_query->have_posts()) :
            while($cat_query->have_posts()) :
              $cat_query->the_post();

              the_title();
              the_excerpt();            
              echo'<a class="button" href="'.get_the_permalink().'">Read more</a>';

            // $response = '<div class="the-post">';
            // $response .= '<h1 class="the-title">';
            // $response .= '<a href="#">'. get_the_title() .'</a>';
            // $response .= '</h1>';
            // $response .= apply_filters( 'the_content', get_the_content() );
            //  $response .= '<a href="#">'. the_post_thumbnail() .'</a>';
            // $response .= '</div>';


            //echo $response;

            endwhile; 

            endif; 

            wp_reset_postdata(); 

            die(1); 
        }
