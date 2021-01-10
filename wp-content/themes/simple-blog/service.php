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

                the_content( );
            endwhile; // End of the loop.
    ?>

</div>
<?php


get_footer();