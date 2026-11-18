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
    wp_enqueue_style(
        'utopia-style',
        get_template_directory_uri() . '/utopia.css',
        array(),
        filemtime( get_template_directory() . '/utopia.css' )
    );
    
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
}
add_action( 'init', 'create_post_types' );

function thevictory_slide_admin_columns( $columns ) {
    if ( isset( $columns['date'] ) ) {
        $columns['date'] = __( 'Last Modified', 'thevictory' );
    }

    return $columns;
}
add_filter( 'manage_slide_posts_columns', 'thevictory_slide_admin_columns' );

function thevictory_slide_admin_date( $date, $post, $column_name, $mode ) {
    if ( 'date' === $column_name && 'slide' === $post->post_type ) {
        return get_the_modified_date( '', $post );
    }

    return $date;
}
add_filter( 'post_date_column_time', 'thevictory_slide_admin_date', 10, 4 );

function admin_init(){
    add_meta_box("spelletjesInformatie", "Spelletje informatie", "meta_options_spelletjes", "ttv_spelletjes", "normal");
    add_meta_box("spelletjesInformatie", "Oefeningen niveau", "meta_options_oefeningen", "ttv_oefeningen", "normal");
}
add_action('admin_init', 'admin_init');

function meta_options_spelletjes(){
    global $post;

    $aantalPersonen = get_post_meta( $post->ID, 'aantal_personen', true );
    $benodigdheden = get_post_meta( $post->ID, 'benodigdheden', true );
    $niveau = get_post_meta( $post->ID, 'niveau', true );
    ?>
<?php wp_nonce_field( 'thevictory_save_custom_posttype', 'thevictory_custom_posttype_nonce' ); ?>
<label>Aantal personen:</label><br />
<input name="aantal_personen" value="<?php echo esc_attr( $aantalPersonen ); ?>" /> <br /><br />

<label>Benodigdheden:</label><br />
<input name="benodigdheden" value="<?php echo esc_attr( $benodigdheden ); ?>" /> <br /><br />

<label>Niveau:</label><br />
<?php
    $optionNiveau = array('Beginner', 'Normaal', 'Gevorderde');

    foreach ($optionNiveau as $speelNiveau) {
        echo '<br><input type="radio" name="niveau" value="', esc_attr( $speelNiveau ), '"', checked( $niveau, $speelNiveau, false ), ' />', esc_html( $speelNiveau );
    }
}

function meta_options_oefeningen(){
    global $post;

    $niveau = get_post_meta( $post->ID, 'niveau', true );
    ?>
<?php wp_nonce_field( 'thevictory_save_custom_posttype', 'thevictory_custom_posttype_nonce' ); ?>
<label>Niveau:</label><br />
<?php
    $optionNiveau = array('Beginner', 'Normaal', 'Gevorderde');

    foreach ($optionNiveau as $speelNiveau) {
        echo '<br><input type="radio" name="niveau" value="', esc_attr( $speelNiveau ), '"', checked( $niveau, $speelNiveau, false ), ' />', esc_html( $speelNiveau );
    }
}

function save_custom_posttype( $post_id ) {
    $post_type = get_post_type( $post_id );
    if ( ! in_array( $post_type, array( 'ttv_spelletjes', 'ttv_oefeningen' ), true ) ) {
        return;
    }

    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        || wp_is_post_autosave( $post_id )
        || wp_is_post_revision( $post_id )
    ) {
        return;
    }

    if ( ! isset( $_POST['thevictory_custom_posttype_nonce'] )
        || ! is_string( $_POST['thevictory_custom_posttype_nonce'] )
        || ! wp_verify_nonce(
            wp_unslash( $_POST['thevictory_custom_posttype_nonce'] ),
            'thevictory_save_custom_posttype'
        )
        || ! current_user_can( 'edit_post', $post_id )
    ) {
        return;
    }

    if ( 'ttv_spelletjes' === $post_type ) {
        if ( isset( $_POST['aantal_personen'] ) && is_string( $_POST['aantal_personen'] ) ) {
            update_post_meta(
                $post_id,
                'aantal_personen',
                sanitize_text_field( wp_unslash( $_POST['aantal_personen'] ) )
            );
        }

        if ( isset( $_POST['benodigdheden'] ) && is_string( $_POST['benodigdheden'] ) ) {
            update_post_meta(
                $post_id,
                'benodigdheden',
                sanitize_text_field( wp_unslash( $_POST['benodigdheden'] ) )
            );
        }
    }

    if ( isset( $_POST['niveau'] ) && is_string( $_POST['niveau'] ) ) {
        $niveau = sanitize_text_field( wp_unslash( $_POST['niveau'] ) );
        if ( in_array( $niveau, array( 'Beginner', 'Normaal', 'Gevorderde' ), true ) ) {
            update_post_meta( $post_id, 'niveau', $niveau );
        }
    }
}
add_action('save_post', 'save_custom_posttype');

function modify_read_more_link() {
    return '<a class="button" href="'. get_permalink() .'" class="Lees meer over ' . get_the_title() . '" aria-label="">lees meer</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

/*=============================================
                BREADCRUMBS
=============================================*/
//  to include in functions.php
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

add_filter( 'woocommerce_coming_soon_banner_text', function( $text ) {
    return 'De website is tijdelijk even offline. We zijn bezig met een nieuwe versie van de kleding. Excuses voor het ongemak.';
} );