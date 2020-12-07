<?php get_header(); ?>

<?php page_banner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'We have several conveniently located and unique campuses.'
)); ?>

<div id="campuses-map"></div>

<?php while (have_posts()) : the_post(); ?>

<div 
    class="marker" 
    data-lat="<?php echo get_field('latitude'); ?>" 
    data-lng="<?php echo get_field('longitude'); ?>"
    data-address="<?php echo get_field('address'); ?>"
    data-campus-name="<?php echo get_the_title(); ?>"
    data-campus-link="<?php echo get_permalink(); ?>"
></div>

<?php endwhile; ?>


<?php get_footer(); ?>