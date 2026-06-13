<?php
/* Front Page Template */
    get_header();
?>
<main class="home" role="main" id="main">
    <div class="home-hero-section">
        <div class="hero-content">
            <h1>Tafeltennisvereniging The Victory</h1>
            <p>The Victory is een gezellige tafeltennisvereniging met rond de 100 leden. We hebben zowel jeugd als
                senioren die tafeltennis spelen. Naast recreatief tafeltennis bieden wij uiteraard ook de
                mogelijkheid
                om competitie te spelen.</p>
            <div class="call-to-action">
                <a class="button" href="/algemene-informatie/openingstijden/">Openingstijden</a>
                <a class="button" href="/algemene-informatie/agenda/">Agenda</a>
            </div>
        </div>
    </div>
    <!-- <div class="home-clinic-section">
        <div class="clinic-content" style="margin: auto;width:60%">
            <h2>Tafeltennis clinic gehad? 3x gratis proefles</h2>
            <p>Wil je na de clinic op school 3x gratis komen trainen? Dat kan! Stuur een mailtje naar <a href="mailto:info@thevictory.nl">info@thevictory.nl</a> of <a href="/the-victory/proefles/">gebruik het formulier</a> dan gaan we dat samen regelen. We hopen je snel te zien.</p>
        </div>
    </div> -->
    <div class="home-philosophy-section">
        <h2>Kijkje nemen?</h2>
        <p>Interesse om eens een balletje te komen slaan of een proefles te volgen? Neem dan even contact met ons op
            zodat we je goed kunnen opvangen.</a></p>
        <p><a href="mailto:info@thevictory.nl">via email</a><br>of <a href="/the-victory/proefles/">het
                contactformulier</a></p>
    </div>
    <div class="home-location-section">
        <h2>Adres</h2>
        <p>'s Gravelandseweg 3a
            <br> 1381 HH Weesp
            <br> T: 0294-417637
            <br> E: <a href="mailto:info@thevictory.nl">info@thevictory.nl</a>
        </p>
    </div>

    <div class="nav-bottom">
        <p><?php posts_nav_link(' | ','<< nieuwere berichten','oudere berichten >>'); ?></p>
    </div>

    <div class="home-featured-instructors-section" id="news">
        <div class="featured-instructors-width-wrapper">
            <?php
                if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                ?>
            <div class="featured-instructor featured-instructor-yellow">
                <?php 
                            if ( has_post_thumbnail() ) : 
                                $thumbnail_id = get_post_thumbnail_id( $post->ID );
                                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                        ?>
                <div class="post-images">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" width="100%" height="100%"
                            alt="<?php echo $alt; ?>">
                    </a>
                </div>
                <?php endif; ?>
                <div class="post-content">
                    <h3><a href="<?php the_permalink(); ?>" rel="bookmark"
                            title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

                    <p>
                        <?php the_content(); ?>
                    </p>
                </div>
            </div>
            <?php 
                endwhile; else : ?>
        </div>

        <!-- The very first "if" tested to see if there were any Posts to -->
        <!-- display.  This "else" part tells what do if there weren't any. -->
        <p><?php esc_html_e( 'Sorry, geen berichten gevonden.' ); ?></p>


        <!-- REALLY stop The Loop. -->
        <?php endif; ?>
    </div>
</main>

<nav class="nav-bottom" role="navigation" aria-label="Archief navigatie">
    <p><?php posts_nav_link(' | ','<< nieuwere berichten','oudere berichten >>'); ?></p>
</nav>

<?php get_footer(); ?>