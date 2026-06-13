<?php

/* Template Name: Tafeltennis Oefeningen Page */

?>

<?php get_header(); ?>

<div class="classes-wrapper">
   <div class="classes-entry">
     <h1>Tafeltennis oefeningen</h1>  
     <p>Hier onder zie je een overzicht van verschillende trainingsvormen bij tafeltennis.</p>    
   </div>
    <?php
        $args = array(
          'post_type' => 'ttv_oefeningen', 
          'post_status' => 'publish',
          'orderby' => 'title',
          'order' => 'ASC',
          'posts_per_page' => 50 
        );

        $loop = new WP_Query( $args );
    
        while ( $loop->have_posts() ) : $loop->the_post();

          $custom = get_post_custom($post->ID); 
          $niveau = strtolower($custom["niveau"][0]);
          
          switch ($niveau) {
            case 'beginner':
              $class = 'green';
              break;
            case 'gevorderde':
              $class = 'pink';
              break;            
            default:
              $class = 'yellow';  
              break;
          }     
          ?>
          <div class="class-container class-container-<?php echo $class; ?>">    
            <h2><?php echo the_title(); ?></h2>
            <div class="class-container-content">                
                <div class="class-instructor-section">
                    <h3>Niveau</h3>
                    <p class="skill-level"><?php echo $custom["niveau"][0]; ?></p> 
                </div>                
                <div class="class-length-section thumbnail">
                  <?php the_post_thumbnail($size = 'medium'); ?>
                </div>
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