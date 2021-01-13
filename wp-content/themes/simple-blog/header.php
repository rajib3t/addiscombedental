<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package simple_blog
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header>
	<?php 
	$logo = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $logo , 'full' );
	$logo_url = $image[0];
		
	?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-4">
                <a class="navbar-brand" href="<?php echo get_option("siteurl"); ?>">
                    <img src="<?php echo $logo_url?>" alt="logo" class="img-fluid">
                </a>
            </div>
            <div class="col-lg-9 col-md-9 col-8">
                <div class="head-top">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6 text-center d-none d-lg-block">
                            <span class="top-addr"><i class="fa fa-map-marker"></i> <?php echo get_option( 'contact_address', '' )?></span>
                        </div>
                        <div class="col-lg-4 col-md-12 text-center col-12">
                            <a href="mailto:<?php echo get_option( 'contact_email', '' )?>" class="top-mail"><i class="fa fa-envelope"></i> <?php echo get_option( 'contact_email', '' )?></a>
                        </div>
                        <div class="col-lg-2 col-md-2 text-right d-none d-lg-block">
                            <div class="social">
                                <a href="#" class="fa fa-facebook"></a>
                                <a href="#" class="fa fa-twitter"></a>
                                <a href="#" class="fa fa-google-plus"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-mid">
                    <nav class="navbar-expand-lg navbar-light align-items-center">
                        <div class="collapse navbar-collapse" id="navbarNav">

						<?php 
								$args = [
                                    'menu'=>'Menu 1',
									'menu_class'=>'navbar-nav',
									'container'=>false,
                                    'walker' => new OWCtheme_Walker_Nav_Menu(),
                                    
								];
								wp_nav_menu($args)
		  				?>
                            
                        </div>
                        <a href="tel:<?php echo get_option( 'contact_phone', '' )?>" class="tel-top"><i class="call-ico"></i> <?php echo get_option( 'contact_phone', '' )?></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span></button>
                    </nav>
                    
                </div>
            </div>
        </div>
    </div>
 </header>

 <?php
 if(is_page_template( 'service.php' )){
     get_template_part('template/header','page');
 }
