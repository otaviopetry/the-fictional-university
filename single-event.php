<?php get_header(); ?>

<?php while (have_posts()) : the_post() ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section">

    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a 
                class="metabox__blog-home-link" 
                href="<?php 
                    $today = date('Ymd');
                    if ( get_field('event_date') < $today) {
                        echo site_url('past-events');
                    } else {
                        echo get_post_type_archive_link('event');
                    }
                ?>"
            >
                <i class="fa fa-home" aria-hidden="true"></i>
                <?php
                    if ( get_field('event_date') < $today) {
                        echo 'Back to Past Events';
                    } else {
                        echo 'Events Home';
                    }
                ?>
            </a> <span class="metabox__main"><?php the_title(); ?>
        </p>
    </div>

    <div>
        <?php the_content(); ?>

        <?php
            $relatedPrograms = get_field('related_programs');

            if ($relatedPrograms) :
        ?>

            <hr class="section-break">
            
            <ul class="link-list min-list">

                <h2 class="headline headline--medium">Related Program(s)</h2>
                
                <?php foreach ($relatedPrograms as $program) : ?>

                    <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>

                <?php endforeach; ?>

            </ul>
        
        <?php endif; ?>

    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>