<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section">

    <?php 
        $parent_id = wp_get_post_parent_id(get_the_ID());
        if ( $parent_id ) : 
    ?>

        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($parent_id); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parent_id); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>

    <?php endif; ?>

    <?php 
        $is_parent = get_pages(array(
            'child_of' => get_the_ID()
        ));
        if ($parent_id or $is_parent) : 
    ?>

        <div class="page-links">
            <h2 class="page-links__title"><a href="<?php echo get_permalink($parent_id); ?>"><?php echo get_the_title($parent_id); ?></a></h2>
            <ul class="min-list">
                <?php
                    if ($parent_id) {
                        $findChildrenOf = $parent_id;
                    } else {
                        $findChildrenOf = get_the_ID();
                    }

                    wp_list_pages(array(
                        'title_li' => NULL,
                        'child_of' => $findChildrenOf,
                        'sort_column' => 'menu_order'
                    ));
                ?>
            </ul>
        </div>

    <?php endif; ?>
   

    <div class="generic-content">
        <form method="get" action="<?php echo esc_url(site_url('/')); ?>" class="search-form">
			<label for="s" class="headline headline--medium">Perform your search:</label>
			<div class="search-form-row">
				<input id="s" name="s" type="search" class="s" placeholder="What are you looking for?">
				<input type="submit" value="Search" class="search-submit">
			</div>
		</form>
    </div>

</div>

<?php endwhile; ?>

<?php get_footer(); ?>