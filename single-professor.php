<?php get_header(); ?>

<?php while (have_posts()) : the_post() ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section">

    <div>

        <div class="generic-content">
            <div class="row group">
                <div class="one-third"><?php the_post_thumbnail('professor-portrait'); ?></div>
                <div class="two-thirds">
					<?php
						$likeCount = new WP_Query(array(
							'post_type' => 'like',
							'meta_query' => array(
								array(
									'key' => 'liked_professor_id',
									'compare' => '=',
									'value' => get_the_ID()
								)
							)
						));

						$userLiked = 'no';

						if (is_user_logged_in()) {
							$checkUserLike = new WP_Query(array(
								'author' => get_current_user_id(),
								'post_type' => 'like',
								'meta_query' => array(
									array(
										'key' => 'liked_professor_id',
										'compare' => '=',
										'value' => get_the_ID()
									)
								)
							));
							if ($checkUserLike->found_posts) {
								$userLiked = 'yes';
							}
						}
					?>

					<span class="like-box" data-like="<?php echo $checkUserLike->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $userLiked; ?>">
						<i class="fa fa-heart-o" aria-hidden="true"></i>
						<i class="fa fa-heart" aria-hidden="true"></i>
						<span class="like-count">
							<?php 
								echo $likeCount->found_posts;
								wp_reset_postdata();
							?>
						</span>
					</span>
					<?php the_content(); ?>
				</div>
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