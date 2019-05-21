<?php
/**
 * Created by PhpStorm.
 * User: aatanasov
 * Date: 4/28/2017
 * Time: 4:06 PM
 */
?>
<div class="blog-post-meta post-meta-top">
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
                    title="<?php esc_html_e("View comments", "rigid")?>"><?php echo get_comments_number() ?></a></span>
	<?php endif; ?>
</div>