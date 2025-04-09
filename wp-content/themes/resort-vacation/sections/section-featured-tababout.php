<?php 
/**
 * Template part for displaying Featured About Section
 *
 * @package Resort Vacation
 */

$resort_vacation_about = get_theme_mod( 'resort_vacation_about_setting',false );?>

<?php if ( $resort_vacation_about ){?>
<div id="about-section" class="section-content py-5">
    <div class="container">
        <?php
         $resort_vacation_featured_mission_section_title = get_theme_mod( 'resort_vacation_featured_mission_section_title' );?>
            <div class="section-title mb-5 text-center">
                <?php if( $resort_vacation_featured_mission_section_title ) { ?>
                    <h3><?php echo esc_html($resort_vacation_featured_mission_section_title); ?></h3>
                <?php } ?>
            </div>
            <div class="owl-carousel">
                <?php 
                    $catergory_name = get_theme_mod('resort_vacation_trending_post_slider_args_');
                    $args = array(
                        'post_type'           => 'post',
                        'category_name'       => $catergory_name,
                        'orderby'             => 'post__in',
                        'ignore_sticky_posts' => true,
                        'posts_per_page'      =>'10',

                    );?>
                    <?php
                    $loop = new WP_Query($args);
                    if ( $loop->have_posts() ) :
                        $i=1;
                        while ($loop->have_posts()) : $loop->the_post(); ?>
                            <div class=" align-self-center px-0">
                                <div class="box">
                                    <?php
                                        if ( has_post_thumbnail() ) : ?>
                                            <div class="image-blog">
                                          <?php the_post_thumbnail();?>
                                           <span><?php
                                            $categories = get_the_category();
                                            if ( ! empty( $categories ) ) {
                                                echo esc_html( $categories[0]->name );   
                                            }
                                            ?></span>
                                          </div>
                                        <?php else:
                                          ?>
                                          <div class="image-blog">
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/banner.jpg'; ?>">
                                            <span><?php
                                            $categories = get_the_category();
                                            if ( ! empty( $categories ) ) {
                                                echo esc_html( $categories[0]->name );   
                                            }
                                            ?></span>
                                          </div>
                                          <?php
                                        endif;
                                      ?>
                                    <div class="box-content">
                                        <div class="row">
                                           <div class="col-lg-8 col-md-8 col-sm-12 col-8 align-self-center">
                                              <h4 class="title mb-2"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                                              <img class="star-img" src="<?php echo get_stylesheet_directory_uri() . '/images/star.png'; ?>">
                                              <div class="room-detail">
                                                <?php if ( get_theme_mod('resort_vacation_room_square_fit_area'.$i) ) : ?>
                                                    <p class="mb-0 room-area"><?php echo esc_html(get_theme_mod('resort_vacation_room_square_fit_area'.$i));?></p>
                                                <?php endif; ?>
                                                <?php if ( get_theme_mod('resort_vacation_room_person'.$i) ) : ?>
                                                    <p class="mb-0 room-person"><?php echo esc_html(get_theme_mod('resort_vacation_room_person'.$i));?></p>
                                                <?php endif; ?>    
                                              </div> 
                                           </div>
                                           <div class="col-lg-4 col-md-4 col-sm-12 col-4 align-self-center left-border">
                                             <?php if ( get_theme_mod('resort_vacation_room_rent'.$i) ) : ?>
                                                <h5 class="room-rent"><?php echo esc_html(get_theme_mod('resort_vacation_room_rent'.$i));?></h5>
                                                <img class="mode-img" src="<?php echo get_stylesheet_directory_uri() . '/images/mode.png'; ?>">
                                              <?php endif; ?>  
                                           </div> 
                                        </div> 
                                    </div>
                                </div>
                                </div>
                        <?php $i++; endwhile;
                    endif;
                ?>
            </div>
    </div>
</div>
<?php } ?>