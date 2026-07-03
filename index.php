<?php
/* Main Template File*/
    get_header();
?>

<main class="main-content-width-wrapper">
	<div class="index-entry">
		<h1><?php echo get_the_title(); ?></h1>

		<div class="main-content">
			<?php
            // Start the loop
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                        the_content();
                endwhile;
            endif;
            ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>