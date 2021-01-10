<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package simple_blog
 */

get_header();
?>

<div class="wrapper inn-page">

<section class="content brd-top">
    <div class="container">
        <div class="col-lg-7 col-md-7 mx-auto text-center">
        <img class="img-fluid" src="<?php echo get_template_directory_uri()?>/images/404.png" alt="">
        <a href="<?php site_url();?>" class="btn btn_theme mx-auto">Back to home</a>
        </div>
    </div>
</section>
</div>

<?php
get_footer();
