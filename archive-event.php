<?php
get_header();
?>

<?php 
    page_banner(array(
        'title' => 'All Events',
        'subtitle' => "See what's going on in our campuses."
    )); 
?>

<div class="container container--narrow page-section">

<?php 
    while (have_posts()) : the_post(); 
        get_template_part('template-parts/content-event');
    endwhile; 
?>

<?php echo paginate_links(); ?>

<hr class="section-break">

<p>Looking for a recap of past events? <a href="<?php echo site_url('past-events'); ?>">Check out our past events recap.</a></p>

</div>

<?php
get_footer();
?>