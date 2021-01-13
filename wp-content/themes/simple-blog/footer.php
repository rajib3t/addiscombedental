<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package simple_blog
 */

?>

<footer>
    <div class="foot-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <h2>Get In Touch</h2>
                    <div class="cont-foot">
                        <p><i class="markar-i"></i><?php echo get_option( 'contact_address', '' )?></p>
                        <p><i class="call-i"></i><a href="tel:<?php echo get_option( 'contact_phone', '' )?>"><?php echo get_option( 'contact_phone', '' )?></a></p>
                        <p><i class="envelope-i"></i><a href="mailto:<?php echo get_option( 'contact_email', '' )?>"><?php echo get_option( 'contact_email', '' )?></a></p>
                    </div>
                    <div class="social d-none d-md-block">
                        <a href="#" class="fa fa-facebook ml-0"></a>
                        <a href="#" class="fa fa-twitter"></a>
                        <a href="#" class="fa fa-google-plus"></a>
                    </div>
                    
                </div>
                <div class="col-lg-3 col-md-3 pl-lg-5 pl-md-5">
                    <h2>quick links</h2>
                    <?php 
								$args = [
									'menu'=>'Footer Menu1',
									'menu_class'=>false,
									'container'=>false,
									'walker' => new OWCtheme_Walker_Footer_Menu()
								];
								wp_nav_menu($args)
		  				?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <h2>treatments</h2>
                    <?php 
								$args = [
									'menu'=>'Footer Menu2',
									'menu_class'=>false,
									'container'=>false,
									'walker' => new OWCtheme_Walker_Footer_Menu()
								];
								wp_nav_menu($args)
		  				?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <h2>opening hours</h2>
                    <span class="sdl-day">
                        Mondays to Fridays <br> from 9:00 am – 5:30 pm <br> Weekends by appointment
                    </span>
					<div class="social d-md-none">
                        <a href="#" class="fa fa-facebook ml-0"></a>
                        <a href="#" class="fa fa-twitter"></a>
                        <a href="#" class="fa fa-google-plus"></a>
                    </div>
                    <img src="<?php echo get_template_directory_uri() ?>/images/care-logo.png" alt="care-logo" class="img-fluid mb-4">
                    <img src="<?php echo get_template_directory_uri() ?>/images/gdc-logo.png" alt="gdc-logo" class="img-fluid mb-4">
                    <img src="<?php echo get_template_directory_uri() ?>/images/nhs-logo.png" alt="nhs-logo" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <div class="foot-btm">
        <div class="container">
            <p>Copyright © <?php echo date('Y')?> <?php echo bloginfo( )?>. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
<script>
      (function($) { // Begin jQuery
  $(function() { // DOM ready
    // If a link has a dropdown, add sub menu toggle.
    $('.navbar-nav li a:not(:only-child)').click(function(e) {
      $(this).siblings('.sub-menu').toggle();
      // Close one dropdown when selecting another
      $('.sub-menu').not($(this).siblings()).hide();
      e.stopPropagation();
    });
    // Clicking away from dropdown will remove the dropdown class
    $('html').click(function() {
      $('.sub-menu').hide();
    });
    // Toggle open and close nav styles on click
    //$('#nav-toggle').click(function() {
      //$('nav ul').slideToggle();
    //});
    // Hamburger to X toggle
    //$('#nav-toggle').on('click', function() {
      //this.classList.toggle('active');
    //});
  }); // end DOM ready
})(jQuery); // end jQuery
  </script>

</body>
</html>
