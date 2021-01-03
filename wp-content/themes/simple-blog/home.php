<?php
/* Template Name: Home Page */
get_header()
?>
<?php
		while ( have_posts() ) :
			the_post();
            remove_filter( 'the_content', 'wpautop');
            the_content();
           add_filter( 'the_content', 'wpautop');

			

		endwhile; // End of the loop.
		?>

<?php
get_footer();