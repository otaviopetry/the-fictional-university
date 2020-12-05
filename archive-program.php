<?php get_header(); ?>

<?php page_banner(array(
    'title' => 'All Programs',
    'subtitle' => 'Check all our graduation programs and find the one that fits you!'
)); ?>

<div class="container container--narrow page-section">

<ul class="link-list min-list">

<?php while (have_posts()) : the_post(); ?>

<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

<?php endwhile; ?>

</ul>

<?php echo paginate_links(); ?>

</div>

<?php
get_footer();
?>