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