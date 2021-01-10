<?php
/**
 * Template Name: Service Page
 */

get_header();
?>
<div class="wrapper inn-page">
    <?php
            while ( have_posts() ) :
                the_post();
                remove_filter( 'the_content', 'wpautop' );
                the_content( );
                add_filter( 'the_content', 'wpautop' );
            endwhile; // End of the loop.
    ?>

</div>
<?php


get_footer();