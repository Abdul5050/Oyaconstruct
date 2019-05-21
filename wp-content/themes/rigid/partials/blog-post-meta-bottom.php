<?php
/**
 * Created by PhpStorm.
 * User: aatanasov
 * Date: 4/28/2017
 * Time: 4:06 PM
 */

// Show or not the author avatar
$rigid_show_author_avatar = false;
if ( ( rigid_get_option( 'show_author_avatar' ) ) || ( is_singular() && ! rigid_get_option( 'show_author_info' ) && rigid_get_option( 'show_author_avatar' ) ) ) {
	$rigid_show_author_avatar = true;
}

$rigid_avatar_img = get_avatar( get_the_author_meta( 'ID' ), 60 );
?>
<div class="blog-post-meta post-meta-bottom">

	<?php if ( ! isset( $rigid_is_latest_posts ) ): ?>
        <span class="posted_by">
            <?php if ( $rigid_show_author_avatar && $rigid_avatar_img ): ?>
	            <?php echo wp_kses_post( $rigid_avatar_img ) ?>
            <?php else: ?>
                <i class="fa fa-user"></i>
            <?php endif; ?>
			<?php echo " ";
			the_author_posts_link(); ?>
        </span>
        <span class="post-meta-date">
            <a href="<?php echo esc_url( get_the_permalink() ) ?>">
                <?php the_time( get_option( 'date_format' ) ); ?>
            </a>
        </span>
	<?php endif; ?>

	<?php if ( is_singular() ): ?>
		<?php if ( $rigid_categories = get_the_category() ): ?>
            <span class="posted_in"><i class="fa fa-folder-open"></i>
				<?php $rigid_lastElmnt = end( $rigid_categories ); ?>
				<?php foreach ( $rigid_categories as $rigid_category ): ?>
                    <a href="<?php echo esc_url( get_category_link( $rigid_category->term_id ) ) ?>"
                       title="<?php echo sprintf( esc_attr__( "View all posts in %s", 'rigid' ), esc_attr( $rigid_category->name ) ) ?>"><?php echo esc_html( $rigid_category->name ) ?></a><?php if ( $rigid_category != $rigid_lastElmnt ): ?>,<?php endif; ?>
				<?php endforeach; ?>
				</span>
		<?php endif; ?>
		<?php if ( ! isset( $rigid_is_latest_posts ) ): ?>
			<?php the_tags( '<i class="fa fa-tags"></i> ' ); ?>
            <span class="count_comments"><i class="fa fa-comments"></i> <a
                        href="<?php echo esc_url( get_comments_link() ) ?>"
                        title="View comments"><?php echo get_comments_number() ?></a></span>
		<?php endif; ?>
	<?php endif; ?>

</div>