<?php 
if ( ! function_exists ( 'thevictory_setup') ) :
    function thevictory_setup() {
        // let WordPress handle the Titles tags
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
    }
endif;
add_action( 'after_setup_theme', 'thevictory_setup' );

/* ---- Register Menus ---- */

function register_thevictory_menus() {
    register_nav_menus(
        array(
            'header-menu' => __( 'Top Menu' ),
            'footer-menu' => __( 'Bottom Menu' )
        )
    );
}
add_action( 'init', 'register_thevictory_menus' );

/* ---- Add Stylesheets ---- */

function thevictory_scripts() { 
    // Enqueue Main Stylesheet
    wp_enqueue_style( 'thevictory_styles', get_stylesheet_uri() );
    // Enqueue Google Fonts, Raleway
    wp_enqueue_style( 'thevictory_google_fonts', 'https://fonts.googleapis.com/css?display=swap&family=Raleway:300,400,400i,700');
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr-custom.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'thevictory_scripts' );

/* ---- Register Widget Areas ---- */

function thevictory_widgets_init() {
    register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'thevictory' ),
		'id'            => 'main-sidebar',
		'description'   => __( 'Widgets wordt getoond bij de twee column template.', 'thevictory' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'thevictory_widgets_init' );

/**
 * bootstrap 5 wp_nav_menu walker
 */ 
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu {
  private $current_item;
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  function start_lvl(&$output, $depth = 0, $args = null) {
    $dropdown_menu_class[] = '';
    foreach($this->current_item->classes as $class) {
      if(in_array($class, $this->dropdown_menu_alignment_values)) {
        $dropdown_menu_class[] = $class;
      }
    }
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      $classes[] = 'dropdown-menu dropdown-menu-end';
    }

    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
    $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
    $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}
register_nav_menu('main-menu', 'Main menu');

/**
 * Custom posttype
 */
function create_post_types() {
    register_post_type( 'ttv_spelletjes',
        array(
           'labels' => array(
                'name' => __( 'Spelletjes' ),
                'singular_name' => __( 'Spelletje' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ),            
            'show_in_rest' => true,
            'rewrite' => array( 'slug' => 'tafeltennis-spelletjes')
        )
    );

    register_post_type( 'ttv_oefeningen',
        array(
           'labels' => array(
                'name' => __( 'Oefeningen' ),
                'singular_name' => __( 'Oefening' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ),            
            'show_in_rest' => true,
            'rewrite' => array( 'slug' => 'tafeltennis-oefeningen' ),

        )
    );

    // register_post_type('slides',
    //     array(
    //         'label' => __( 'Slide' ),
	// 	    'description' => __( 'TV Slides'),
    //         'labels' => array(
    //             'name' => __( 'Slides' ),
    //             'singular_name' => __( 'Slide' ),
    //             'add_new' => __( 'Nieuwe slide' ),
    //             'add_new_item' => __( 'Nieuwe slide toevoegen' ),
    //             'edit_item' => __( 'Bewerk slide' ),
    //             'new_item' => __( 'Nieuwe slide' ),
    //             'search_items' => __( 'Zoek slide' ),
    //             'not_found' =>  __( 'Geen slides gevonden' ),
    //         ),
    //         'public' => true,
    //         'has_archive' => false,
    //         'supports' => array('title','editor' ),
    //         'show_in_rest' => true,
    //         'rewrite' => array('slug' => 'slides'),
    //         'menu_icon' => 'dashicons-store',
    //         'exclude_from_search'   => true,
    //     )
    // );
}
add_action( 'init', 'create_post_types' );

function admin_init(){
    add_meta_box("spelletjesInformatie", "Spelletje informatie", "meta_options_spelletjes", "ttv_spelletjes", "normal");
    add_meta_box("spelletjesInformatie", "Oefeningen niveau", "meta_options_oefeningen", "ttv_oefeningen", "normal");
}
add_action('admin_init', 'admin_init');
   
function meta_options_spelletjes(){
    global $post;

    $custom = get_post_custom($post->ID);
    $aantalPersonen = $custom["aantal_personen"][0];
    $benodigdheden = $custom["benodigdheden"][0];
    $niveau = $custom["niveau"][0];
    ?>
<label>Aantal personen:</label><br />
<input name="aantal_personen" value="<?php echo $aantalPersonen; ?>" /> <br /><br />

<label>Benodigdheden:</label><br />
<input name="benodigdheden" value="<?php echo $benodigdheden; ?>" /> <br /><br />

<label>Niveau:</label><br />
<?php
    $optionNiveau = array('Beginner', 'Normaal', 'Gevorderde');

    foreach ($optionNiveau as $speelNiveau) {                    
        echo '<br><input type="radio" name="niveau" value="', $speelNiveau, '"', $niveau == $speelNiveau ? ' checked="checked"' : '', ' />', $speelNiveau;
    }
}

function meta_options_oefeningen(){
    global $post;

    $custom = get_post_custom($post->ID);
   
    $niveau = $custom["niveau"][0];
    ?>
<label>Niveau:</label><br />
<?php
    $optionNiveau = array('Beginner', 'Normaal', 'Gevorderde');

    foreach ($optionNiveau as $speelNiveau) {                    
        echo '<br><input type="radio" name="niveau" value="', $speelNiveau, '"', $niveau == $speelNiveau ? ' checked="checked"' : '', ' />', $speelNiveau;
    }
}

function save_custom_posttype(){
    global $post;

    update_post_meta($post->ID, "aantal_personen", $_POST["aantal_personen"]);
    update_post_meta($post->ID, "benodigdheden", $_POST["benodigdheden"]);
    update_post_meta($post->ID, "niveau", $_POST["niveau"]);
}
add_action('save_post', 'save_custom_posttype');

function modify_read_more_link() {
    return '<a class="button" href="'. get_permalink() .'" class="Lees meer over ' . get_the_title() . '" aria-label="">lees meer</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

// remove_filter('template_redirect', 'redirect_canonical');  

// add_filter('redirect_canonical', 'no_redirect_on_404');
// function no_redirect_on_404($redirect_url)
// {
//     if (is_404()) {
//         return false;
//     }
//     return $redirect_url;
// }

/*=============================================
                BREADCRUMBS
=============================================*/
//  to include in functions.php
function the_breadcrumb() {
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = '&raquo;'; // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb

    global $post;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<nav aria-label="Breadcrumb" id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></nav>';
        }
    } else {
        echo '<nav aria-label="Breadcrumb" id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
        } elseif (is_search()) {
            echo $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo $cats;
                if ($showCurrent == 1) {
                    echo $before . get_the_title() . $after;
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo $before . get_the_title() . $after;
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) {
                    echo ' ' . $delimiter . ' ';
                }
            }
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_tag()) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            echo $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</nav>';
    }
} // end the_breadcrumb()

function custom_breadcrumbs() {      
    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Homepage';
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
           
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $last_category = end(array_values($category));
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
              
            } else {
                  
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
                   
            } else {
                   
                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
               
            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_year() ) {
               
            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
               
        } else if ( is_search() ) {
           
            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
           
        } elseif ( is_404() ) {
               
            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }
       
        echo '</ul>';
           
    }
       
}

function ah_breadcrumb() {
    // Check if is front/home page, return
    if ( is_front_page() ) {
      return;
    }
  
    // Define
    global $post;
    $custom_taxonomy  = ''; // If you have custom taxonomy place it here
  
    $defaults = array(
      'seperator'   =>  '&#187;',
      'id'          =>  'ah-breadcrumb',
      'classes'     =>  'ah-breadcrumb',
      'home_title'  =>  esc_html__( 'Home', '' )
    );
  
    $sep  = '<li class="seperator">'. esc_html( $defaults['seperator'] ) .'</li>';
  
    // Start the breadcrumb with a link to your homepage
    echo '<ul id="'. esc_attr( $defaults['id'] ) .'" class="'. esc_attr( $defaults['classes'] ) .'">';
  
    // Creating home link
    echo '<li class="item"><a href="'. get_home_url() .'">'. esc_html( $defaults['home_title'] ) .'</a></li>' . $sep;
  
    if ( is_single() ) {
  
      // Get posts type
      $post_type = get_post_type();
  
      // If post type is not post
      if( $post_type != 'post' ) {
  
        $post_type_object   = get_post_type_object( $post_type );
        $post_type_link     = get_post_type_archive_link( $post_type );
  
        echo '<li class="item item-cat"><a href="'. $post_type_link .'">'. $post_type_object->labels->name .'</a></li>'. $sep;
  
      }
  
      // Get categories
      $category = get_the_category( $post->ID );
  
      // If category not empty
      if( !empty( $category ) ) {
  
        // Arrange category parent to child
        $category_values      = array_values( $category );
        $get_last_category    = end( $category_values );
        // $get_last_category    = $category[count($category) - 1];
        $get_parent_category  = rtrim( get_category_parents( $get_last_category->term_id, true, ',' ), ',' );
        $cat_parent           = explode( ',', $get_parent_category );
  
        // Store category in $display_category
        $display_category = '';
        foreach( $cat_parent as $p ) {
          $display_category .=  '<li class="item item-cat">'. $p .'</li>' . $sep;
        }
  
      }
  
      // If it's a custom post type within a custom taxonomy
      $taxonomy_exists = taxonomy_exists( $custom_taxonomy );
  
      if( empty( $get_last_category ) && !empty( $custom_taxonomy ) && $taxonomy_exists ) {
  
        $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
        $cat_id         = $taxonomy_terms[0]->term_id;
        $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
        $cat_name       = $taxonomy_terms[0]->name;
  
      }
  
      // Check if the post is in a category
      if( !empty( $get_last_category ) ) {
  
        echo $display_category;
        echo '<li class="item item-current">'. get_the_title() .'</li>';
  
      } else if( !empty( $cat_id ) ) {
  
        echo '<li class="item item-cat"><a href="'. $cat_link .'">'. $cat_name .'</a></li>' . $sep;
        echo '<li class="item-current item">'. get_the_title() .'</li>';
  
      } else {
  
        echo '<li class="item-current item">'. get_the_title() .'</li>';
  
      }
  
    } else if( is_archive() ) {
  
      if( is_tax() ) {
        // Get posts type
        $post_type = get_post_type();
  
        // If post type is not post
        if( $post_type != 'post' ) {
  
          $post_type_object   = get_post_type_object( $post_type );
          $post_type_link     = get_post_type_archive_link( $post_type );
  
          echo '<li class="item item-cat item-custom-post-type-' . $post_type . '"><a href="' . $post_type_link . '">' . $post_type_object->labels->name . '</a></li>' . $sep;
  
        }
  
        $custom_tax_name = get_queried_object()->name;
        echo '<li class="item item-current">'. $custom_tax_name .'</li>';
  
      } else if ( is_category() ) {
  
        $parent = get_queried_object()->category_parent;
  
        if ( $parent !== 0 ) {
  
          $parent_category = get_category( $parent );
          $category_link   = get_category_link( $parent );
  
          echo '<li class="item"><a href="'. esc_url( $category_link ) .'">'. $parent_category->name .'</a></li>' . $sep;
  
        }
  
        echo '<li class="item item-current">'. single_cat_title( '', false ) .'</li>';
  
      } else if ( is_tag() ) {
  
        // Get tag information
        $term_id        = get_query_var('tag_id');
        $taxonomy       = 'post_tag';
        $args           = 'include=' . $term_id;
        $terms          = get_terms( $taxonomy, $args );
        $get_term_name  = $terms[0]->name;
  
        // Display the tag name
        echo '<li class="item-current item">'. $get_term_name .'</li>';
  
      } else if( is_day() ) {
  
        // Day archive
  
        // Year link
        echo '<li class="item-year item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') . ' Archives</a></li>' . $sep;
  
        // Month link
        echo '<li class="item-month item"><a href="'. get_month_link( get_the_time('Y'), get_the_time('m') ) .'">'. get_the_time('M') .' Archives</a></li>' . $sep;
  
        // Day display
        echo '<li class="item-current item">'. get_the_time('jS') .' '. get_the_time('M'). ' Archives</li>';
  
      } else if( is_month() ) {
  
        // Month archive
  
        // Year link
        echo '<li class="item-year item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') . ' Archives</a></li>' . $sep;
  
        // Month Display
        echo '<li class="item-month item-current item">'. get_the_time('M') .' Archives</li>';
  
      } else if ( is_year() ) {
  
        // Year Display
        echo '<li class="item-year item-current item">'. get_the_time('Y') .' Archives</li>';
  
      } else if ( is_author() ) {
  
        // Auhor archive
  
        // Get the author information
        global $author;
        $userdata = get_userdata( $author );
  
        // Display author name
        echo '<li class="item-current item">'. 'Author: '. $userdata->display_name . '</li>';
      } else {
        echo '<li class="item item-current">'. post_type_archive_title() .'</li>';
      }
    } else if ( is_page() ) {
      // Standard page
      if( $post->post_parent ) {
  
        // If child page, get parents
        $anc = get_post_ancestors( $post->ID );
  
        // Get parents in the right order
        $anc = array_reverse( $anc );
  
        // Parent page loop
        if ( !isset( $parents ) ) $parents = null;
        foreach ( $anc as $ancestor ) {
  
          $parents .= '<li class="item-parent item"><a href="'. get_permalink( $ancestor ) .'">'. get_the_title( $ancestor ) .'</a></li>' . $sep;
  
        }
  
        // Display parent pages
        echo $parents;
  
        // Current page
        echo '<li class="item-current item">'. get_the_title() .'</li>';
  
      } else {
  
        // Just display current page if not parents
        echo '<li class="item-current item">'. get_the_title() .'</li>';
  
      }
  
    } else if ( is_search() ) {
  
      // Search results page
      echo '<li class="item-current item">Search results for: '. get_search_query() .'</li>';
  
    } else if ( is_404() ) {
  
      // 404 page
      echo '<li class="item-current item">' . 'Error 404' . '</li>';
  
    }
  
    // End breadcrumb
    echo '</ul>';
  }

  /**
 * Replace the home link URL
 */
add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
function woo_custom_breadrumb_home_url() {
    return 'https://www.thevictory.nl/winkel';
}