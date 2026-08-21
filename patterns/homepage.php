<?php
/**
 * Title: Homepage
 * Slug: thevictory/homepage
 * Categories: featured
 */
?>
<!-- wp:group {"tagName":"main","className":"home","layout":{"type":"constrained"}} -->
<main class="wp-block-group home">
  <!-- wp:cover {"dimRatio":20,"className":"hero home-hero-section","layout":{"type":"constrained"}} -->
  <div class="wp-block-cover hero home-hero-section">
    <span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span>
    <div class="wp-block-cover__inner-container">
      <!-- wp:heading {"level":1} -->
      <h1 class="wp-block-heading">Tafeltennisvereniging The Victory</h1>
      <!-- /wp:heading -->
      <!-- wp:paragraph -->
      <p>The Victory is een gezellige tafeltennisvereniging met rond de 100 leden. We hebben zowel jeugd als senioren die tafeltennis spelen. Naast recreatief tafeltennis bieden wij uiteraard ook de mogelijkheid om competitie te spelen.</p>
      <!-- /wp:paragraph -->
      <!-- wp:buttons {"className":"call-to-action"} -->
      <div class="wp-block-buttons call-to-action">
        <!-- wp:button {"url":"/algemene-informatie/openingstijden/"} -->
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Openingstijden</a></div>
        <!-- /wp:button -->
        <!-- wp:button {"url":"/algemene-informatie/agenda/"} -->
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Agenda</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->
    </div>
  </div>
  <!-- /wp:cover -->

  <!-- wp:columns {"className":"info-section"} -->
  <div class="wp-block-columns info-section">
    <!-- wp:column {"className":"home-philosophy-section"} -->
    <div class="wp-block-column home-philosophy-section">
      <!-- wp:heading {"level":2} -->
      <h2 class="wp-block-heading">Kijkje nemen?</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph -->
      <p>Interesse om eens een balletje te komen slaan of een proefles te volgen? Neem contact met ons op zodat we je goed kunnen opvangen.</p>
      <!-- /wp:paragraph -->
      <!-- wp:paragraph -->
      <p><a href="mailto:info@thevictory.nl">via email</a><br>of <a href="/the-victory/proefles/">het contactformulier</a></p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->
    <!-- wp:column {"className":"home-location-section"} -->
    <div class="wp-block-column home-location-section">
      <!-- wp:heading {"level":2} -->
      <h2 class="wp-block-heading">Adres</h2>
      <!-- /wp:heading -->
      <!-- wp:paragraph -->
      <p>'s Gravelandseweg 3a<br>1381 HH Weesp<br>T: 0294-417637<br>E: <a href="mailto:info@thevictory.nl">info@thevictory.nl</a></p>
      <!-- /wp:paragraph -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->

  <!-- wp:query {"query":{"perPage":6,"postType":"post","order":"desc","orderBy":"date"},"className":"home-featured-instructors-section"} -->
  <div class="wp-block-query home-featured-instructors-section">
    <!-- wp:post-template {"className":"featured-instructors-width-wrapper"} -->
      <!-- wp:group {"className":"featured-instructor featured-instructor-yellow"} -->
      <div class="wp-block-group featured-instructor featured-instructor-yellow">
        <!-- wp:post-featured-image {"isLink":true} /-->
        <!-- wp:post-title {"level":3,"isLink":true} /-->
        <!-- wp:post-content /-->
      </div>
      <!-- /wp:group -->
    <!-- /wp:post-template -->
    <!-- wp:query-pagination /-->
  </div>
  <!-- /wp:query -->
</main>
<!-- /wp:group -->
