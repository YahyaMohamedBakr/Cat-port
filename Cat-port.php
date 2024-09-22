<?php
/*
Plugin Name: Custom Category Grid
Description: Plugin to display categories in a 3-column grid for a specific post type.
Version: 1.0
Author: Yahya Bakr
*/

function enqueue_bootstrap_in_plugin() {
    //  Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    //  Bootstrap JS  jQuery
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_in_plugin');

function category_grid_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'portfolio', //  defulte post type
    ), $atts, 'category_grid');

    // get categorise
    $categories = get_terms(array(
        'taxonomy' => $atts['post_type'] . '-cat', 
        'hide_empty' => true,
    ));

    if (empty($categories) || is_wp_error($categories)) {
        return 'No categories found.';
    }

 // start grid with Bootstrap
 $output = '<div class="container"><div class="row">';

 foreach ($categories as $category) {
     // get image
     $thumbnail_id = get_term_meta($category->term_id, '', true);
     $image_url = wp_get_attachment_url($thumbnail_id['image'][0]);

     //get discription
     $description = wp_trim_words($category->description, 10, '...');

     // cards loop whith Bootstrap
     $output .= '
     <div class="col-md-4 mb-4">
         <div class="card h-100">
             <img src="' . esc_url($image_url) . '" class="card-img-top" alt="' . esc_attr($category->name) . '">
             <div class="card-body text-center">
                 <h5 class="card-title">' . esc_html($category->name) . '</h5>
                 <p class="card-text">' . esc_html($description) . '</p>
                 <a href="' . esc_url(get_term_link($category)) . '" class="btn btn-primary">المزيد</a>
             </div>
         </div>
     </div>';
 }

 // end grid 
 $output .= '</div></div>';

 return $output;

}

//[category_grid post_type="portfolio"]
add_shortcode('category_grid', 'category_grid_shortcode');
