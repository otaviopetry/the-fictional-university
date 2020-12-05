<?php get_header(); ?>

<?php while (have_posts()) : the_post() ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
        <div class="page-banner__intro">
        <p>Don't forget to make me dynamic.</p>
        </div>
    </div>  
</div>

<div class="container container--narrow page-section">

    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
                <i class="fa fa-home" aria-hidden="true"></i> All Programs
            </a> <span class="metabox__main"><?php the_title(); ?>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content(); ?>
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

        <?php while ($programEvents->have_posts()) : $programEvents->the_post(); ?>
        
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month">
                    <?php 
                        $eventDate = new DateTime(get_field('event_date'));
                        echo $eventDate->format('M');
                    ?>
                </span>
                <span class="event-summary__day">
                    <?php
                        echo $eventDate->format('d');
                    ?>
                </span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <p>
                    <?php 
                        if (has_excerpt()) {
                            // use get_the_excerpt here to deal with markup ourselves
                            echo get_the_excerpt();
                        } else {
                            echo wp_trim_words(get_the_content(), 18);
                        }
                    ?>                
                    <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
                </p>
            </div>
        </div>

        <?php endwhile; ?>

    <?php endif; wp_reset_postdata(); ?>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>