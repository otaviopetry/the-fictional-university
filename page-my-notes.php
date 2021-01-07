<?php 
	if (!is_user_logged_in()) {
		wp_redirect(esc_url(site_url('/')));
	}

	get_header(); 
?>

<?php while (have_posts()) : the_post(); ?>

<?php page_banner(); ?>

<div class="container container--narrow page-section">

	<div class="create-note">
		<h2 class="headline headline--medium">Create New Note</h2>
		<input type="text" class="new-note-title" placeholder="Note title">
		<textarea class="new-note-body" name="" id="" cols="30" rows="10" placeholder="What's your note?"></textarea>
		<span class="submit-note">Create note</span>
	</div>

	<ul class="min-list link-list" id="my-notes">
		<?php
			$userNotes = new WP_Query(array(
				'post_type' => 'note',
				'posts_per_page' => -1,
				'author' => get_current_user_id()
			));

			while ($userNotes->have_posts()) :
				$userNotes->the_post();
		?>
			<li data-id="<?php the_ID(); ?>">
				<input readonly class="note-title-field" type="text" value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>">
				<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
				<span class="delete-note"><i class="fa fa-pencil" aria-hidden="true"></i> Delete</span>
				<textarea readonly class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>
				<span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
			</li>
		<?php endwhile; ?>
	</ul>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>