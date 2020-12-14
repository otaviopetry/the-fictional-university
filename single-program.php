<?php get_header(); ?>

<?php while (have_posts()) : the_post() ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section">

    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
                <i class="fa fa-home" aria-hidden="true"></i> All Programs
            </a> <span class="metabox__main"><?php the_title(); ?>
        </p>
    </div>

    <div class="generic-content">
        <?php the_field('main_body_content'); ?>
    </div>

    <?php
        $relatedProfessors = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' 
                    // here we add the quotes to check for the exact post id which will be wrapped in quotes inside the serialized array
                )
            )
        ));
    ?>

    <?php if ($relatedProfessors->have_posts()) : ?>

        <hr class="section-break">

        <h2 class="headline headline--medium"> <?php echo get_the_title(); ?> Professors</h2>

        <ul class="professor-cards">

            <?php while ($relatedProfessors->have_posts()) : $relatedProfessors->the_post(); ?>
            
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professor-landscape'); ?>" />
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>

            <?php endwhile; ?>

        </ul>

    <?php endif; wp_reset_postdata(); ?>

    <?php        
        $today = date('Ymd'); 
        $programEvents = new WP_Query(array(
            'posts_per_page' => 2,
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
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' 
                    // here we add the quotes to check for the exact post id which will be wrapped in quotes inside the serialized array
                )
            )
        ));
    ?>

    <?php if ($programEvents->have_posts()) : ?>

        <hr class="section-break">

        <h2 class="headline headline--medium">Upcoming <?php echo get_the_title(); ?> events</h2>

        <?php 
            while ($programEvents->have_posts()) : $programEvents->the_post();
                get_template_part('template-parts/content-event');
            endwhile;
        ?>

    <?php endif; wp_reset_postdata(); ?>

    <hr class="section-break">

    <?php
        $relatedCampuses = get_field('related_campus');

        if ($relatedCampuses) {
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campuses:</h2>';

            echo '<ul class="min-list link-list"';

            foreach ($relatedCampuses as $campus) {
                ?><li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li><?php
            }

            echo '</ul>';
        }
    ?>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>