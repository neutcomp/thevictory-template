<?php

/* Template Name: Tafeltennis Spelletjes Page */

?>

<?php get_header(); ?>

<div class="classes-wrapper" id="main">
   <div class="classes-entry">
     <h1>Tafeltennis spelletjes</h1>  
     <p>Hier onder vind u een lijst van heel veel verschillende tafeltennis spelletjes. Deze tafeltennis spelletjes kunt u op de camping, toernooien of op een kindertafeltennis feest uitvoeren. De tafeltennis spelletjes zijn soms voor 2 of meer spelers. Ook staat er bij elke spel hoeveel tafels er nodig zijn. De moeilijkheidsgraad bij de tafeltennis spelletjes geven aan of het voor beginners of gevorderden zijn te spelen.</p>    
   </div>
    <?php
        $args = array(
          'post_type' => 'ttv_spelletjes', 
          'post_status' => 'publish',
          'meta_key' => 'niveau',
          'orderby' => 'meta_value',
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
                    <h3>Aantal personen</h3>
                    <p class="instructor-name"><?php echo $custom["aantal_personen"][0]; ?></p>
                </div>
                <div class="class-skill-section">
                    <h3>Niveau</h3>
                    <p class="skill-level"><?php echo $custom["niveau"][0]; ?></p> 
                </div>
                <div class="class-instructor-section">
                    <h3>Benodigdheden</h3>
                    <p class="instructor-name"><?php echo $custom["benodigdheden"][0]; ?></p>
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