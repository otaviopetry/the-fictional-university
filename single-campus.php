<?php get_header(); ?>

<?php while (have_posts()) : the_post() ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section campus-page">

    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
                <i class="fa fa-home" aria-hidden="true"></i> All Campuses
            </a> <span class="metabox__main"><?php the_title(); ?>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content(); ?>

        <div id="campus-map"></div>

        <div 
            class="marker" 
            data-lat="<?php echo get_field('latitude'); ?>" 
            data-lng="<?php echo get_field('longitude'); ?>"
            data-address="<?php echo get_field('address'); ?>"
            data-campus-name="<?php echo get_the_title(); ?>"
            data-campus-link="<?php echo get_permalink(); ?>"
        ></div>
    </div>

    <?php
        $relatedPrograms = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'program',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_campus',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' 
                    // here we add the quotes to check for the exact post id which will be wrapped in quotes inside the serialized array
                )
            )
        ));
    ?>

    <?php if ($relatedPrograms->have_posts()) : ?>

        <hr class="section-break">

        <h2 class="headline headline--medium">Programs Available at this Campus</h2>

        <ul class="min-list link-list">

            <?php while ($relatedPrograms->have_posts()) : $relatedPrograms->the_post(); ?>
            
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>

            <?php endwhile; ?>

        </ul>

    <?php endif; wp_reset_postdata(); ?>

    <?php        
        $today = date('Ymd'); 
        $programEvents = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'event',
            'orderby' => 'meta_value_num',
            'meta_key' => 'event_date',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ),
                array(
                    'key' => 'event_location',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' 
                    // here we add the quotes to check for the exact post id which will be wrapped in quotes inside the serialized array
                )
            )
        ));
    ?>

    <?php if ($programEvents->have_posts()) : ?>

        <hr class="section-break">

        <h2 class="headline headline--medium">Upcoming events at <?php echo get_the_title(); ?> Campus</h2>

        <?php 
            while ($programEvents->have_posts()) : $programEvents->the_post();
                get_template_part('template-parts/content-event');
            endwhile; 
        ?>

    <?php endif; wp_reset_postdata(); ?>
</div>

<?php endwhile; ?>

<?php get_footer('single-campus'); ?>