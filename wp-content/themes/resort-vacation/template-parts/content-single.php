<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package resort_vacation
 */
$resort_vacation_single_meta_setting  = get_theme_mod( 'resort_vacation_single_post_meta_setting' , true );
$resort_vacation_single_content_setting  = get_theme_mod( 'resort_vacation_single_post_content_setting' , true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php

		if ( 'post' === get_post_type() ) : ?>
		<?php
		if ( $resort_vacation_single_meta_setting ){ ?>
			<div class="entry-meta">
				<?php resort_vacation_posted_on(); ?>
			</div><!-- .entry-meta -->
	    <?php } ?>
		<?php
		endif; ?>
	</header><!-- .entry-header -->
    <?php
	if ( $resort_vacation_single_content_setting ){ ?>
		<div class="entry-content" itemprop="text">
			<?php
			if( is_single()){
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'resort-vacation' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
				}else{
				$resort_vacation_excerpt_length = get_theme_mod('resort_vacation_post_excerpt_length' ,20);
				    $resort_vacation_content = get_the_content();
				    echo esc_html(wp_trim_words($resort_vacation_content, $resort_vacation_excerpt_length));
				}
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'resort-vacation' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	<?php } ?>
</article><!-- #post-## -->