<?php get_header(); ?>

<?php while (have_posts()) : the_post() ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section">

    <div>

        <div class="generic-content">
            <div class="row group">
                <div class="one-third"><?php the_post_thumbnail('professor-portrait'); ?></div>
                <div class="two-thirds"><?php the_content(); ?></div>
            </div>
        </div>
        
        <?php
            $relatedPrograms = get_field('related_programs');

            if ($relatedPrograms) :
        ?>

            <hr class="section-break">
            
            <ul class="link-list min-list">

                <h2 class="headline headline--medium">Subject(s) Taught</h2>
                
                <?php foreach ($relatedPrograms as $program) : ?>

                    <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>

                <?php endforeach; ?>

            </ul>
        
        <?php endif; ?>

    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>