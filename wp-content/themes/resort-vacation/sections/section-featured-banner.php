<?php
/**
 * Banner Section
 * 
 * @package resort_vacation
 */
$resort_vacation_slider     = get_theme_mod( 'resort_vacation_slider_setting',false );

$resort_vacation_args = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'category_name' =>  get_theme_mod('resort_vacation_blog_slide_category'),
  'posts_per_page' => 3,
); ?>
<?php if ( $resort_vacation_slider ){?>
  <div class="banner">
    <div class="owl-carousel">
      <?php $resort_vacation_arr_posts = new WP_Query( $resort_vacation_args );
      if ( $resort_vacation_arr_posts->have_posts() ) :
        while ( $resort_vacation_arr_posts->have_posts() ) :
          $resort_vacation_arr_posts->the_post();
          ?>
          <div class="banner_inner_box">
            <?php
              if ( has_post_thumbnail() ) :
                the_post_thumbnail();
              else:
                ?>
                <div class="image-container">
                  <img src="<?php echo get_stylesheet_directory_uri() . '/images/banner.jpg'; ?>">
                </div>
                <?php
              endif;
            ?>
              <div class="banner_box">
                  <?php if ( get_theme_mod('resort_vacation_slider_text_extra') ) : ?>
                    <h5><?php echo esc_html(get_theme_mod('resort_vacation_slider_text_extra'));?></h5>
                  <?php endif; ?>
                  <h3 class="my-3"><?php the_title(); ?></a></h3>
                  <p class="mb-0"><?php echo wp_trim_words( get_the_content(), 30 ); ?></p>
                  <div class="slider-buttons">
                      <p class="btn-green mt-4">
                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php esc_html_e('View All Travels','resort-vacation'); ?></a>
                      </p>
                </div>
              </div>
          </div>
        <?php
      endwhile;
      wp_reset_postdata();
      endif; ?>
    </div>
  </div>
<?php } ?>