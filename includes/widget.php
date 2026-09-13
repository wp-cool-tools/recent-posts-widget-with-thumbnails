<?php
if (!defined('ABSPATH')) exit;

echo wp_kses_post( $args[ 'before_widget' ] ) . "\n";
?>
<div id="rpwwt-<?php echo esc_attr( $args[ 'widget_id' ] );?>" class="rpwwt-widget">
<?php 
	if ( $title ) echo wp_kses_post( $args[ 'before_title' ] . $title . $args[ 'after_title' ] ) . "\n";
	if ( $this->defaults[ 'is_nav_widget' ] ) { ?>
	<nav role="navigation" aria-label="<?php echo esc_attr( $this->get_aria_nav_label( $title ) ); ?>">
<?php } ?>
	<ul>
<?php while ( $r->have_posts() ) { $r->the_post(); ?>
		<li<?php 
		$classes = array();
		if ( is_sticky() ) { 
			$classes[] = 'rpwwt-sticky';
		}
		if ( $bools[ 'print_post_categories' ] ) {
			$cats = get_the_category();
			if ( is_array( $cats ) and $cats ) {
				foreach ( $cats as $category ) {
					$classes[] = $category->slug;
				}
			}
		}
		if ( $classes ) {
			echo ' class="', esc_attr( join( ' ', $classes ) ), '"';
		}
		$aria_current = $queried_object_id === $r->post->ID ? ' aria-current="page"' : '';
		?>><a href="<?php echo esc_url( get_permalink() ); ?>"<?php echo wp_kses_data( $this->customs[ 'link_target' ] ); echo wp_kses_data( $aria_current ); ?>><?php
		if ( $bools[ 'show_thumb' ] ) {
			$thumb = '';
			// if always only the default image
			if ( $bools[ 'use_default_only' ] ) {
				$thumb = $default_img;
			// if only first image
			} elseif ( $bools[ 'only_1st_img' ] ) {
				// try to find and to display the first post image and to return success
				$thumb = $this->the_first_post_image( $attr );
			} else {
				// look for featured image
				if ( has_post_thumbnail() ) {
					// if there is featured image then show it
					$thumb = get_the_post_thumbnail( null, $this->customs[ 'thumb_dimensions' ], $attr );
				} else {
					// if user wishes first image trial
					if ( $bools[ 'try_1st_img' ] ) {
						// try to find and to display the first post image and to return success
						$thumb = $this->the_first_post_image( $attr );
					} // if try_1st_img 
				} // if has_post_thumbnail
			} // if only_1st_img
			if ( $thumb ) {
				echo wp_kses_post( $thumb );
			// if there is no image 
			} else {
				// if user allows default image then
				if ( $bools[ 'use_default' ] ) {
					echo wp_kses_post( $default_img );
				} // if use_default
			} // if thumb
		} // if show_thumb
		// show title if wished
		if ( ! $bools[ 'hide_title' ] ) {
			?><span class="rpwwt-post-title"><?php
			$post_title = $this->get_the_trimmed_post_title();
			if ( $post_title ) {
				echo esc_html( $post_title );
			} else {
				printf(
					'%s<span class="screen-reader-text"> %s %d</span>',
					esc_html( $this->defaults[ 'no_title' ] ),
					esc_html( $this->defaults[ 'Post' ] ),
					absint( get_the_ID() )
				);
			}
			?></span><?php
		}
		?></a><?php 
		if ( $bools[ 'show_author' ] ) {
			?><div class="rpwwt-post-author"><?php echo esc_html( $this->get_the_author() ); ?></div><?php 
		}
		if ( $bools[ 'show_categories' ] ) {
			?><div class="rpwwt-post-categories"><?php echo wp_kses_post( $this->get_the_categories( $r->post->ID ) ); ?></div><?php
		}
		if ( $bools[ 'show_date' ] ) {
			?><div class="rpwwt-post-date"><?php echo esc_html( get_the_date() ); ?></div><?php
		}
		if ( $bools[ 'show_excerpt' ] ) {
			?><div class="rpwwt-post-excerpt"><?php echo wp_kses_post( $this->get_the_trimmed_excerpt() ); ?></div><?php
		}
		if ( $bools[ 'show_comments_number' ] ) {
			?><div class="rpwwt-post-comments-number"><?php echo esc_html( get_comments_number_text() ); ?></div><?php
		} 
	?></li>
<?php } // while() ?>
	</ul>
<?php if ( $this->defaults[ 'is_nav_widget' ] ) { ?>
	</nav>
<?php } ?>
</div><!-- .rpwwt-widget -->
<?php echo wp_kses_post( $args[ 'after_widget' ] ); ?>
