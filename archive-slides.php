<?php

/* Template Name: Slides */

?>

<?php get_header(); ?>

<div class="classes-wrapper">
    <div class="classes-entry">
        <h1>TV slides</h1>
        <p>Hier onder vind u een lijst van TV Slides</p>
    </div>
    <?php
        $args = array(
          'post_type' => 'slides', 
          'post_status' => 'publish',
          'order' => 'ASC',
          'posts_per_page' => 50 
        );

        $loop = new WP_Query( $args );
    
        while ( $loop->have_posts() ) : $loop->the_post();

          $custom = get_post_custom($post->ID);
          ?>
    <div class="class-container class-container-<?php echo $class; ?>">
        <h2><?php echo the_title(); ?></h2>
        <div class="class-container-content">
            <div class="class-length-section">
                <h3>Beschrijving</h3>
                <p class="class-description"><?php the_content(); ?></p>
            </div>
        </div>
    </div>

    <?php
        endwhile;
        wp_reset_postdata();
     ?>
</div>

<?php get_footer(); ?>