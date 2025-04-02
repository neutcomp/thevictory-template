<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo get_bloginfo('name'); ?></title>
		<meta name="description" content="<?php echo get_bloginfo('description'); ?>">
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('style.css'); ?>" />
  </head>
  <body>
    <div class="container">
      <div class="row">
        <a href="/"
          ><img
            class="logo"
            src="<?php echo get_theme_file_uri('./img/the-victory-logo.webp'); ?>"
            alt="The Victory logo"
        /></a>

        <nav class="nav">
          <ul>
            <li class="nav__items">
              <a href="/algemene-informatie/">Informatie</a>
            </li>
            <li class="nav__items">
              <a href="/algemene-informatie/agenda/">Agenda</a>
            </li>
            <li class="nav__items"><a href="/tafeltennis/">Tafeltennis</a></li>
            <li class="nav__items">
              <a
                href="https://www.flickr.com/photos/bjornvanderneut/collections/72157704032038694/"
                >Foto's</a
              >
            </li>
            <li class="nav__items">
              <a href="#"
                ><img
                  src="<?php echo get_theme_file_uri('img/google-translate-flags.png'); ?>"
                  alt="Google Translate"
              /></a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
