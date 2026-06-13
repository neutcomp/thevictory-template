<?php

if(isset($_POST['submitted'])) {
	if(trim($_POST['contactName']) === '') {
		$nameError = 'Geef je naam op';
		$hasError = true;
	} else {
		$name = trim($_POST['contactName']);
	}

	if(trim($_POST['email']) === '')  {
		$emailError = 'Geef je emailadres op';
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
		$emailError = 'Het emailadres is ongeldig';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	if(trim($_POST['comments']) === '') {
		$commentError = 'Geef een bericht op';
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['comments']));
		} else {
			$comments = trim($_POST['comments']);
		}
	}

	if(!isset($hasError)) {

		// reCAPTCHA validation
		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

			// Google secret API
			$secretAPIkey = '6Ldo4WIiAAAAABWM3QCAe2sSqmAJc2hJngi9VV2l';

			// reCAPTCHA response verification
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretAPIkey.'&response='.$_POST['g-recaptcha-response']);

			// Decode JSON data
			$response = json_decode($verifyResponse);
				if($response->success){

					$emailTo = get_bloginfo('admin_email');
					$subject = 'Contactformulier: Bericht van '.$name;
					$body = "Naam: $name \n\nEmail: $email \n\nBericht: $comments";
					$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

					wp_mail($emailTo, $subject, $body, $headers);
					$emailSent = true;
				} else {
					$commentError = 'Robot verification mislukt. Probeer opnieuw.';
					$hasError = true;
				}       
		} else{ 
			$commentError = 'Vergeet niet de reCAPTCHA box aan te vinken';
			$hasError = true;
		} 
	}
}

/* Template Name: Contact */
    get_header();
?>

<div class="main-content-width-wrapper">
        <div class="two-column-entry">
        
            <main class="main-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-content">
						<?php if(isset($emailSent) && $emailSent == true) { ?>
							<div class="thanks">
								<p>Bedankt we zullen zo spoedig mogelijk iets van ons laten horen.</p>
							</div>
						<?php } else { ?>
							<?php the_content(); ?>
							<?php if(isset($hasError) || isset($captchaError)) { ?>
								<p class="error">Sorry een fout heeft opgetreden.<p>
							<?php } ?>

						<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
							<ul class="contactform">
							<li>
								<label for="contactName">Naam:</label>
								<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" />
								<?php if($nameError != '') { ?>
									<span class="error"><?=$nameError;?></span>
								<?php } ?>
							</li>

							<li>
								<label for="email">Email</label>
								<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredField email" />
								<?php if($emailError != '') { ?>
									<span class="error"><?=$emailError;?></span>
								<?php } ?>
							</li>

							<li><label for="commentsText">Bericht:</label>
								<textarea name="comments" id="commentsText" rows="20" cols="30" class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
								<?php if($commentError != '') { ?>
									<span class="error"><?=$commentError;?></span>
								<?php } ?>
							</li>

							<li>
								<div class="g-recaptcha" data-sitekey="6Ldo4WIiAAAAAMBZ4NsCuO5Ua79eXlDjGiHs5L09"></div>
							<li>
								<button type="submit" name="submitted" id="submitted" class="button enroll-button">verzenden</button>
							</li>
						</ul>
					</form>
					<br />
				<?php } ?>
				</div><!-- .entry-content -->
			</div><!-- .post -->

				<?php endwhile; endif; ?>
            </main>
        </div>
        <?php get_sidebar('main-sidebar'); ?>
    </div>
    
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php get_footer(); ?>