<?php
/* Main Footer Template */
?>

<footer class="footer" id="footer" role="contentinfo">
	<nav role="navigation" aria-label="Footer menu">
		<?php
                        wp_nav_menu( $arg = array (
                            'menu_class' => 'footer-navigation',
                            'theme_location' => 'footer-menu'
                        ));
                    ?>
	</nav>
	<p class="copyright">
		<small>The Victory &copy; 's Gravelandseweg 3a, 1381 HH Weesp</small>
	</p>
	<p class="boekhouding">
		<a href="https://www.e-boekhouden.nl/?c=vssp" title="e-Boekhouden.nl" target="blank">
			<img src="https://cdn.e-boekhouden.nl/img/sponsor/nl/180x90px.png" alt="e-Boekhouden.nl">
		</a>
	</p>
</footer>
<?php wp_footer(); ?>
</div> <!-- end of u-container  -->

</body>

</html>