<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package resort_vacation
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function resort_vacation_body_classes( $classes ) {
  global $post;
  
    if( !is_page_template( 'template-home.php' ) ){
        $classes[] = 'inner';
        // Adds a class of group-blog to blogs with more than 1 published author.
    }

	if ( is_multi_author() ) {
		$classes[] = 'group-blog ';
	}

    // Adds a class of custom-background-image to sites with a custom background image.
    if ( get_background_image() ) {
        $classes[] = 'custom-background-image';
    }
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
        $classes[] = 'custom-background-color';
    }
    

    if( resort_vacation_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || 'product' === get_post_type() ) && ! is_active_sidebar( 'shop-sidebar' ) ){
        $classes[] = 'full-width';
    }    

    // Adds a class of hfeed to non-singular pages.
    if ( ! is_page() ) {
    	$classes[] = 'hfeed ';
    }
  
    if( is_404() ||  is_search() ){
        $classes[] = 'full-width';
    }
  
    if( ! is_active_sidebar( 'right-sidebar' ) ) {
        $classes[] = 'full-width'; 
    }

	return $classes;
}
add_filter( 'body_class', 'resort_vacation_body_classes' );

 /**
 * 
 * @link http://www.altafweb.com/2011/12/remove-specific-tag-from-php-string.html
 */
function resort_vacation_strip_single( $tag, $string ){
    $string=preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
    $string=preg_replace('/<\/'.$tag.'>/i', '', $string);
    return $string;
}

if ( ! function_exists( 'resort_vacation_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function resort_vacation_excerpt_more($more) {
  return is_admin() ? $more : ' &hellip; ';
}
endif;
add_filter( 'excerpt_more', 'resort_vacation_excerpt_more' );

if( ! function_exists( 'resort_vacation_footer_credit' ) ):
/**
 * Footer Credits
*/
function resort_vacation_footer_credit() {
    $resort_vacation_copyright_text = get_theme_mod('resort_vacation_footer_copyright_text');

    $resort_vacation_text = '<div class="site-info"><div class="container"><span class="copyright">';
    if ($resort_vacation_copyright_text) {
        $resort_vacation_text .= wp_kses_post($resort_vacation_copyright_text); 
    } else {
        $resort_vacation_text .= esc_html__('&copy; ', 'resort-vacation') . date_i18n(esc_html__('Y', 'resort-vacation')); 
        $resort_vacation_text .= ' <a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>' . esc_html__('. All Rights Reserved.', 'resort-vacation');
    }
    $resort_vacation_text .= '</span>';
    $resort_vacation_text .= '<span class="by"> <a href="' . esc_url('https://www.themeignite.com/products/free-resort-wordpress-theme') . '" rel="nofollow" target="_blank">' . RESORT_VACATION_THEME_NAME . '</a>' . esc_html__(' By ', 'resort-vacation') . '<a href="' . esc_url('https://themeignite.com/') . '" rel="nofollow" target="_blank">' . esc_html__('Themeignite', 'resort-vacation') . '</a>.';
    $resort_vacation_text .= sprintf(esc_html__(' Powered By %s', 'resort-vacation'), '<a href="' . esc_url(__('https://wordpress.org/', 'resort-vacation')) . '" target="_blank">WordPress</a>.');
    if (function_exists('the_privacy_policy_link')) {
        $resort_vacation_text .= get_the_privacy_policy_link();
    }
    $resort_vacation_text .= '</span></div></div>';
    echo apply_filters('resort_vacation_footer_text', $resort_vacation_text);
}
add_action('resort_vacation_footer', 'resort_vacation_footer_credit');
endif;


/**
 * Is Woocommerce activated
*/
if ( ! function_exists( 'resort_vacation_woocommerce_activated' ) ) {
  function resort_vacation_woocommerce_activated() {
    if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
  }
}

if( ! function_exists( 'resort_vacation_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function resort_vacation_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required = ( $req ? " required" : '' );
    $author   = ( $req ? __( 'Name*', 'resort-vacation' ) : __( 'Name', 'resort-vacation' ) );
    $email    = ( $req ? __( 'Email*', 'resort-vacation' ) : __( 'Email', 'resort-vacation' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'resort-vacation' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $author ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'resort-vacation' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $email ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'resort-vacation' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'resort-vacation' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'resort_vacation_change_comment_form_default_fields' );

if( ! function_exists( 'resort_vacation_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function resort_vacation_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'resort-vacation' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'resort-vacation' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'resort_vacation_change_comment_form_defaults' );

if( ! function_exists( 'resort_vacation_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function resort_vacation_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;

if ( ! function_exists( 'resort_vacation_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function resort_vacation_get_fallback_svg( $resort_vacation_post_thumbnail ) {
    if( ! $resort_vacation_post_thumbnail ){
        return;
    }
    
    $resort_vacation_image_size = resort_vacation_get_image_sizes( $resort_vacation_post_thumbnail );
     
    if( $resort_vacation_image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $resort_vacation_image_size['width'] ); ?> <?php echo esc_attr( $resort_vacation_image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $resort_vacation_image_size['width'] ); ?>" height="<?php echo esc_attr( $resort_vacation_image_size['height'] ); ?>" style="fill:#dedddd;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

function resort_vacation_enqueue_google_fonts() {

    require get_template_directory() . '/inc/wptt-webfont-loader.php';

    wp_enqueue_style(
        'google-fonts-merienda',
        wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Merienda:wght@300..900&family=Sail&display=swap' ),
        array(),
        '1.0'
    );

    wp_enqueue_style(
        'google-fonts-roboto',
        wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Merienda:wght@300..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Sail&display=swap' ),
        array(),
        '1.0'
    );

}
add_action( 'wp_enqueue_scripts', 'resort_vacation_enqueue_google_fonts' );


if( ! function_exists( 'resort_vacation_site_branding' ) ) :
/**
 * Site Branding
*/
function resort_vacation_site_branding(){
    $resort_vacation_logo_site_title = get_theme_mod( 'header_site_title', 1 );
    $resort_vacation_tagline = get_theme_mod( 'header_tagline', false );
    $resort_vacation_logo_width = get_theme_mod('logo_width', 100); // Retrieve the logo width setting

    ?>
    <div class="site-branding text-center">
        <?php 
        // Check if custom logo is set and display it
        if (function_exists('has_custom_logo') && has_custom_logo()) {
            the_custom_logo();
        } else {
            if ($resort_vacation_logo_site_title) {
                if (is_front_page()) { ?>
                    <h1 class="site-title" itemprop="name">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                    </h1>
                <?php } else { ?>
                    <p class="site-title" itemprop="name">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                    </p>
                <?php }
            }
            
            if ($resort_vacation_tagline) {
                $resort_vacation_description = get_bloginfo('description', 'display');
                if ($resort_vacation_description || is_customize_preview()) { ?>
                    <p class="site-description" itemprop="description"><?php echo $resort_vacation_description; ?></p>
                <?php }
            }
        }
        ?>
    </div>

    <?php
}
endif;

/**
 * Site Navigation
*/
if( ! function_exists( 'resort_vacation_navigation' ) ) :
function resort_vacation_navigation(){ ?>
    <?php $resort_vacation_header_image = get_header_image(); ?>
        <?php if (!empty($resort_vacation_header_image)) : ?>
            <div class="header-menu-inner" style="background-image: url('<?php echo esc_url($resort_vacation_header_image); ?>') !important; background-size: 100%;">
        <?php else : ?>
            <div class="header-menu-inner">
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-5 col-md-1 align-self-center text-center">
                        <nav class="main-navigation" id="site-navigation"  role="navigation">
                            <?php 
                            wp_nav_menu( array( 
                                'theme_location' => 'primary1', 
                                'menu_id' => 'primary-menu' 
                            ) ); 
                            ?>
                        </nav>
                    </div>
                    <div class="col-lg-2 col-md-12 align-self-center logo-main">
                      <?php resort_vacation_site_branding(); ?> 
                    </div>
                    <div class="col-lg-5 col-md-1 align-self-center text-center">
                        <nav class="main-navigation" id="site-navigation"  role="navigation">
                            <?php 
                            wp_nav_menu( array( 
                                'theme_location' => 'primary2', 
                                'menu_id' => 'primary-menu-1' 
                            ) ); 
                            ?>
                        </nav>
                    </div>
                </div>
            </div>
    <?php
}
endif;


/**
 * Header Start
*/
if( ! function_exists( 'resort_vacation_top_header' ) ) :
function resort_vacation_top_header(){
    $resort_vacation_header_setting     = get_theme_mod( 'resort_vacation_header_setting', false );
    $resort_vacation_phone        = get_theme_mod( 'resort_vacation_header_phone' );
    $resort_vacation_social_icon  = get_theme_mod( 'resort_vacation_social_icon_setting', false);

    if ( $resort_vacation_header_setting ){?>
        <div class="top-header">
            <div class="container">
                <div class="row top-bg">
                    <div class="col-xl-5 col-lg-4 col-md-4 col-sm-12 col-12 align-self-center contact-box">
                        <?php if ( $resort_vacation_phone ){?>
                            <span><a href="tel:<?php echo esc_attr($resort_vacation_phone);?>"><i class="<?php echo esc_attr(get_theme_mod('resort_vacation_phone_icon','fas fa-phone')); ?>"></i><?php esc_html_e( 'For Booking Call us @', 'resort-vacation' ); ?> <?php echo esc_html( $resort_vacation_phone);?></a></span>
                        <?php } ?>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 align-self-center text-lg-end">
                        <?php if ( $resort_vacation_social_icon ){?>
                            <span class="social-links">
                              <?php 
                                $resort_vacation_social_link1 = get_theme_mod( 'resort_vacation_social_link_1' );
                                $resort_vacation_social_link2 = get_theme_mod( 'resort_vacation_social_link_2' );
                                $resort_vacation_social_link3 = get_theme_mod( 'resort_vacation_social_link_3' );
                                $resort_vacation_social_link4 = get_theme_mod( 'resort_vacation_social_link_4' );
                                $resort_vacation_social_link5 = get_theme_mod( 'resort_vacation_social_link_5' );
                                $resort_vacation_social_link6 = get_theme_mod( 'resort_vacation_social_link_6' );

                                if ( ! empty( $resort_vacation_social_link1 ) ) {
                                  echo '<a class="social1" href="' . esc_url( $resort_vacation_social_link1 ) . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                                }
                                if ( ! empty( $resort_vacation_social_link2 ) ) {
                                  echo '<a class="social2" href="' . esc_url( $resort_vacation_social_link2 ) . '" target="_blank"><i class="fab fa-twitter"></i></a>';
                                } 
                                if ( ! empty( $resort_vacation_social_link3 ) ) {
                                  echo '<a class="social3" href="' . esc_url( $resort_vacation_social_link3 ) . '" target="_blank"><i class="fab fa-instagram"></i></a>';
                                }
                                if ( ! empty( $resort_vacation_social_link4 ) ) {
                                  echo '<a class="social4" href="' . esc_url( $resort_vacation_social_link4 ) . '" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                                }
                                if ( ! empty( $resort_vacation_social_link5 ) ) {
                                  echo '<a class="social5" href="' . esc_url( $resort_vacation_social_link5 ) . '" target="_blank"><i class="fab fa-youtube"></i></a>';
                                }
                                if ( ! empty( $resort_vacation_social_link6 ) ) {
                                  echo '<a class="social6" href="' . esc_url( $resort_vacation_social_link6 ) . '" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                                }
                              ?>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-12 align-self-center text-lg-end royal">
                        <?php if( get_theme_mod('resort_vacation_show_hide_search',false) != ''){ ?>
                            <div class="search-body">
                                <button type="button" class="search-show"><i class="<?php echo esc_attr(get_theme_mod('resort_vacation_search_icon','fas fa-search')); ?>"></i></button>
                            </div>
                            <div class="searchform-inner">
                                <?php get_search_form(); ?>
                                <button type="button" class="close"aria-label="<?php esc_attr_e( 'Close', 'resort-vacation' ); ?>"><span aria-hidden="true">X</span></button>
                            </div>
                        <?php } ?>
                            <p class="mb-0">
                              <?php if(class_exists('woocommerce')){ ?>
                                <?php if (is_user_logged_in()) : ?>
                                  <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"><i class="fas fa-sign-out-alt px-lg-2"></i><?php esc_html_e( 'Logout', 'resort-vacation' ); ?></a>
                                <?php else : ?>
                                  <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"><i class="far fa-user px-lg-2"></i><?php esc_html_e( 'Login', 'resort-vacation' ); ?></a>
                                <?php endif;?>
                              <?php } ?>
                            </p>
                        <?php if( get_theme_mod('resort_vacation_sign_url',false) != ''){ ?>
                            <p class="account-btn my-2 text-center">
                              <a target="_blank" href="<?php echo esc_attr(get_theme_mod('resort_vacation_sign_url')); ?>"><i class="<?php echo esc_attr(get_theme_mod('resort_vacation_myaccount_icon','fas fa-user')); ?> me-2"></i><?php echo esc_html_e('Sign Up','resort-vacation'); ?><span class="screen-reader-text"><?php esc_html_e( 'Sign Up','resort-vacation' );?></span></a>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } 
}
endif;


/**
 * Header Start
*/
if( ! function_exists( 'resort_vacation_header' ) ) :
function resort_vacation_header(){
    ?>
    <div id="page-site-header">
        <header id="masthead" class="site-header header-inner" role="banner">
            <div id="main-header" class="header-menu">
                <?php resort_vacation_top_header(); ?>
                <div class="container">
                    
                    <?php resort_vacation_navigation(); ?>
                </div>
            </div>        
        </header>
    </div>
    <?php
}
endif;
add_action( 'resort_vacation_header', 'resort_vacation_header', 20 );


//  Customizer Control
if (class_exists('WP_Customize_Control') && ! class_exists( 'Resort_Vacation_Image_Radio_Control' ) ) {
    /**
    * Customize sidebar layout control.
    */
    class Resort_Vacation_Image_Radio_Control extends WP_Customize_Control {

        public function render_content() {

            if (empty($this->choices))
                return;

            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <ul class="controls" id='resort-vacation-img-container'>
                <?php
                foreach ($this->choices as $value => $label) :
                    $class = ($this->value() == $value) ? 'resort-vacation-radio-img-selected resort-vacation-radio-img-img' : 'resort-vacation-radio-img-img';
                    ?>
                    <li style="display: inline;">
                        <label>
                            <input <?php $this->link(); ?>style = 'display:none' type="radio" value="<?php echo esc_attr($value); ?>" name="<?php echo esc_attr($name); ?>" <?php
                              $this->link();
                              checked($this->value(), $value);
                              ?> />
                            <img src='<?php echo esc_url($label); ?>' class='<?php echo esc_attr($class); ?>' />
                        </label>
                    </li>
                    <?php
                endforeach;
                ?>
            </ul>
            <?php
        }
    }
}
// Exit if WP_Customize_Control does not exsist.
if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return null;
}

class Resort_Vacation_Changeable_Icon extends WP_Customize_Control{
    public $type = 'icon';
    public function render_content(){
        ?>
        <label>
            <span class="customize-control-title">
                <?php echo esc_html( $this->label ); ?>
            </span>

            <?php if($this->description){ ?>
            <span class="description customize-control-description">
                <?php echo wp_kses_post($this->description); ?>
            </span>
            <?php } ?>
            
            <div class="selected-icon">
                <i class="<?php echo esc_attr($this->value()); ?>"></i>
                <span><i class="fa fa-angle-down"></i></span>
            </div>
            <ul class="icon-list clearfix">
                <div class="social-icon-search">
                    <input class="socialInput" type="text" placeholder="<?php echo esc_attr_x( 'Search Icon', 'placeholder','resort-vacation' ); ?>">
                </div>
                <?php
                $Resort_Vacation_Changeable_Icon_array = Resort_Vacation_Changeable_Icon_array();
                foreach ($Resort_Vacation_Changeable_Icon_array as $Resort_Vacation_Changeable_Icon) {
                        $icon_class = $this->value() == $Resort_Vacation_Changeable_Icon ? 'icon-active' : '';
                        echo '<li class='.esc_attr($icon_class).'><i class="'.esc_attr($Resort_Vacation_Changeable_Icon).'"></i>
                        <span class="resort_vacation-social-class">'.esc_attr($Resort_Vacation_Changeable_Icon).'</span>
                        </li>';
                    }
                ?>
            </ul>
            <input type="hidden" value="<?php $this->value(); ?>" <?php $this->link(); ?> />
        </label>
    <?php
    }
}

function resort_vacation_icon_customize_js() {     
    wp_enqueue_style( 'font-awesome-1', esc_url(get_template_directory_uri()).'/css/fontawesome-all.css');
    wp_enqueue_script( 'resort-vacation-customize-icon', esc_url(get_template_directory_uri()).'/js/customize-icon.js', array("jquery"),'', true  );
}
add_action( 'customize_controls_enqueue_scripts', 'resort_vacation_icon_customize_js' );

if(!function_exists('Resort_Vacation_Changeable_Icon_array')){
    function Resort_Vacation_Changeable_Icon_array(){
        return array("fas fa-arrow-up", 
        "fab fa-500px",
        "fab fa-accessible-icon",
        "fab fa-accusoft",
        "fas fa-address-book", 
        "far fa-address-book",
        "fas fa-address-card",
        "far fa-address-card",
        "fas fa-adjust",
        "fab fa-adn",
        "fab fa-adversal",
        "fab fa-affiliatetheme",
        "fab fa-algolia",
        "fas fa-align-center",
        "fas fa-align-justify",
        "fas fa-align-left",
        "fas fa-align-right",
        "fab fa-amazon",
        "fas fa-ambulance",
        "fas fa-american-sign-language-interpreting",
        "fab fa-amilia",
        "fas fa-anchor",
        "fab fa-android",
        "fab fa-angellist",
        "fas fa-angle-double-down",
        "fas fa-angle-double-left",
        "fas fa-angle-double-right",
        "fas fa-angle-double-up",
        "fas fa-angle-down",
        "fas fa-angle-left",
        "fas fa-angle-right",
        "fas fa-angle-up",
        "fab fa-angrycreative",
        "fab fa-angular",
        "fab fa-app-store",
        "fab fa-app-store-ios",
        "fab fa-apper",
        "fab fa-apple",
        "fab fa-apple-pay",
        "fas fa-archive",
        "fas fa-arrow-alt-circle-down", 
        "far fa-arrow-alt-circle-down",
        "fas fa-arrow-alt-circle-left", 
        "far fa-arrow-alt-circle-left",
        "fas fa-arrow-alt-circle-right", 
        "far fa-arrow-alt-circle-right",
        "fas fa-arrow-alt-circle-up", 
        "far fa-arrow-alt-circle-up",
        "fas fa-arrow-circle-down",
        "fas fa-arrow-circle-left",
        "fas fa-arrow-circle-right",
        "fas fa-arrow-circle-up",
        "fas fa-arrow-down",
        "fas fa-arrow-left",
        "fas fa-arrow-right",
        "fas fa-arrow-up",
        "fas fa-arrows-alt",
        "fas fa-arrows-alt-h",
        "fas fa-arrows-alt-v",
        "fas fa-assistive-listening-systems",
        "fas fa-asterisk",
        "fab fa-asymmetrik",
        "fas fa-at",
        "fab fa-audible",
        "fas fa-audio-description",
        "fab fa-autoprefixer",
        "fab fa-avianex",
        "fab fa-aviato",
        "fab fa-aws",
        "fas fa-backward",
        "fas fa-balance-scale",
        "fas fa-ban",
        "fab fa-bandcamp",
        "fas fa-barcode",
        "fas fa-bars",
        "fas fa-bath",
        "fas fa-battery-empty",
        "fas fa-battery-full",
        "fas fa-battery-half",
        "fas fa-battery-quarter",
        "fas fa-battery-three-quarters",
        "fas fa-bed",
        "fas fa-beer",
        "fab fa-behance",
        "fab fa-behance-square",
        "fas fa-bell", "far fa-bell",
        "fas fa-bell-slash", 
        "far fa-bell-slash",
        "fas fa-bicycle",
        "fab fa-bimobject",
        "fas fa-binoculars",
        "fas fa-birthday-cake",
        "fab fa-bitbucket",
        "fab fa-bitcoin",
        "fab fa-bity",
        "fab fa-black-tie",
        "fab fa-blackberry",
        "fas fa-blind",
        "fab fa-blogger",
        "fab fa-blogger-b",
        "fab fa-bluetooth",
        "fab fa-bluetooth-b",
        "fas fa-bold",
        "fas fa-bolt",
        "fas fa-bomb",
        "fas fa-book",
        "fas fa-bookmark", 
        "far fa-bookmark",
        "fas fa-braille",
        "fas fa-briefcase",
        "fab fa-btc",
        "fas fa-bug",
        "fas fa-building", 
        "far fa-building",
        "fas fa-bullhorn",
        "fas fa-bullseye",
        "fab fa-buromobelexperte",
        "fas fa-bus",
        "fab fa-buysellads",
        "fas fa-calculator",
        "fas fa-calendar", 
        "far fa-calendar",
        "fas fa-calendar-alt", 
        "far fa-calendar-alt",
        "fas fa-calendar-check", 
        "far fa-calendar-check",
        "fas fa-calendar-minus", 
        "far fa-calendar-minus",
        "fas fa-calendar-plus", 
        "far fa-calendar-plus",
        "fas fa-calendar-times", 
        "far fa-calendar-times",
        "fas fa-camera",
        "fas fa-camera-retro",
        "fas fa-car",
        "fas fa-caret-down",
        "fas fa-caret-left",
        "fas fa-caret-right",
        "fas fa-caret-square-down", 
        "far fa-caret-square-down",
        "fas fa-caret-square-left", 
        "far fa-caret-square-left",
        "fas fa-caret-square-right", 
        "far fa-caret-square-right",
        "fas fa-caret-square-up", 
        "far fa-caret-square-up",
        "fas fa-caret-up",
        "fas fa-cart-arrow-down",
        "fas fa-cart-plus",
        "fab fa-cc-amex",
        "fab fa-cc-apple-pay",
        "fab fa-cc-diners-club",
        "fab fa-cc-discover",
        "fab fa-cc-jcb",
        "fab fa-cc-mastercard",
        "fab fa-cc-paypal",
        "fab fa-cc-stripe",
        "fab fa-cc-visa",
        "fab fa-centercode",
        "fas fa-certificate",
        "fas fa-chart-area",
        "fas fa-chart-bar", 
        "far fa-chart-bar",
        "fas fa-chart-line",
        "fas fa-chart-pie",
        "fas fa-check",
        "fas fa-check-circle", 
        "far fa-check-circle",
        "fas fa-check-square", 
        "far fa-check-square",
        "fas fa-chevron-circle-down",
        "fas fa-chevron-circle-left",
        "fas fa-chevron-circle-right",
        "fas fa-chevron-circle-up",
        "fas fa-chevron-down",
        "fas fa-chevron-left",
        "fas fa-chevron-right",
        "fas fa-chevron-up",
        "fas fa-child",
        "fab fa-chrome",
        "fas fa-circle", 
        "far fa-circle",
        "fas fa-circle-notch",
        "fas fa-clipboard", 
        "far fa-clipboard",
        "fas fa-clock", 
        "far fa-clock",
        "fas fa-clone", 
        "far fa-clone",
        "fas fa-closed-captioning", 
        "far fa-closed-captioning",
        "fas fa-cloud",
        "fas fa-cloud-download-alt",
        "fas fa-cloud-upload-alt",
        "fab fa-cloudscale",
        "fab fa-cloudsmith",
        "fab fa-cloudversify",
        "fas fa-code",
        "fas fa-code-branch",
        "fab fa-codepen",
        "fab fa-codiepie",
        "fas fa-coffee",
        "fas fa-cog",
        "fas fa-cogs",
        "fas fa-columns",
        "fas fa-comment", 
        "far fa-comment",
        "fas fa-comment-alt", 
        "far fa-comment-alt",
        "fas fa-comments", 
        "far fa-comments",
        "fas fa-compass", 
        "far fa-compass",
        "fas fa-compress",
        "fab fa-connectdevelop",
        "fab fa-contao",
        "fas fa-copy", 
        "far fa-copy",
        "fas fa-copyright", 
        "far fa-copyright",
        "fab fa-cpanel",
        "fab fa-creative-commons",
        "fas fa-credit-card", 
        "far fa-credit-card",
        "fas fa-crop",
        "fas fa-crosshairs",
        "fab fa-css3",
        "fab fa-css3-alt",
        "fas fa-cube",
        "fas fa-cubes",
        "fas fa-cut",
        "fab fa-cuttlefish",
        "fab fa-d-and-d",
        "fab fa-dashcube",
        "fas fa-database",
        "fas fa-deaf",
        "fab fa-delicious",
        "fab fa-deploydog",
        "fab fa-deskpro",
        "fas fa-desktop",
        "fab fa-deviantart",
        "fab fa-digg",
        "fab fa-digital-ocean",
        "fab fa-discord",
        "fab fa-discourse",
        "fab fa-dochub",
        "fab fa-docker",
        "fas fa-dollar-sign",
        "fas fa-dot-circle", "far fa-dot-circle",
        "fas fa-download",
        "fab fa-draft2digital",
        "fab fa-dribbble",
        "fab fa-dribbble-square",
        "fab fa-dropbox",
        "fab fa-drupal",
        "fab fa-dyalog",
        "fab fa-earlybirds",
        "fab fa-edge",
        "fas fa-edit", 
        "far fa-edit",
        "fas fa-eject",
        "fas fa-ellipsis-h",
        "fas fa-ellipsis-v",
        "fab fa-ember",
        "fab fa-empire",
        "fas fa-envelope", 
        "far fa-envelope",
        "fas fa-envelope-open", 
        "far fa-envelope-open",
        "fas fa-envelope-square",
        "fab fa-envira",
        "fas fa-eraser",
        "fab fa-erlang",
        "fab fa-etsy",
        "fas fa-euro-sign",
        "fas fa-exchange-alt",
        "fas fa-exclamation",
        "fas fa-exclamation-circle",
        "fas fa-exclamation-triangle",
        "fas fa-expand",
        "fas fa-expand-arrows-alt",
        "fab fa-expeditedssl",
        "fas fa-external-link-alt",
        "fas fa-external-link-square-alt",
        "fas fa-eye",
        "fas fa-eye-dropper",
        "fas fa-eye-slash", "far fa-eye-slash",
        "fab fa-facebook",
        "fab fa-facebook-f",
        "fab fa-facebook-messenger",
        "fab fa-facebook-square",
        "fas fa-fast-backward",
        "fas fa-fast-forward",
        "fas fa-fax",
        "fas fa-female",
        "fas fa-fighter-jet",
        "fas fa-file", "far fa-file",
        "fas fa-file-alt", "far fa-file-alt",
        "fas fa-file-archive", "far fa-file-archive",
        "fas fa-file-audio", "far fa-file-audio",
        "fas fa-file-code", "far fa-file-code",
        "fas fa-file-excel", "far fa-file-excel",
        "fas fa-file-image", "far fa-file-image",
        "fas fa-file-pdf", "far fa-file-pdf",
        "fas fa-file-powerpoint", "far fa-file-powerpoint",
        "fas fa-file-video", "far fa-file-video",
        "fas fa-file-word", "far fa-file-word",
        "fas fa-film",
        "fas fa-filter",
        "fas fa-fire",
        "fas fa-fire-extinguisher",
        "fab fa-firefox",
        "fab fa-first-order",
        "fab fa-firstdraft",
        "fas fa-flag", "far fa-flag",
        "fas fa-flag-checkered",
        "fas fa-flask",
        "fab fa-flickr",
        "fab fa-fly",
        "fas fa-folder", "far fa-folder",
        "fas fa-folder-open", "far fa-folder-open",
        "fas fa-font",
        "fab fa-font-awesome",
        "fab fa-font-awesome-alt",
        "fab fa-font-awesome-flag",
        "fab fa-fonticons",
        "fab fa-fonticons-fi",
        "fab fa-fort-awesome",
        "fab fa-fort-awesome-alt",
        "fab fa-forumbee",
        "fas fa-forward",
        "fab fa-foursquare",
        "fab fa-free-code-camp",
        "fab fa-freebsd",
        "fas fa-frown", "far fa-frown",
        "fas fa-futbol", "far fa-futbol",
        "fas fa-gamepad",
        "fas fa-gavel",
        "fas fa-gem", "far fa-gem",
        "fas fa-genderless",
        "fab fa-get-pocket",
        "fab fa-gg",
        "fab fa-gg-circle",
        "fas fa-gift",
        "fab fa-git",
        "fab fa-git-square",
        "fab fa-github",
        "fab fa-github-alt",
        "fab fa-github-square",
        "fab fa-gitkraken",
        "fab fa-gitlab",
        "fab fa-gitter",
        "fas fa-glass-martini",
        "fab fa-glide",
        "fab fa-glide-g",
        "fas fa-globe",
        "fab fa-gofore",
        "fab fa-goodreads",
        "fab fa-goodreads-g",
        "fab fa-google",
        "fab fa-google-drive",
        "fab fa-google-play",
        "fab fa-google-plus",
        "fab fa-google-plus-g",
        "fab fa-google-plus-square",
        "fab fa-google-wallet",
        "fas fa-graduation-cap",
        "fab fa-gratipay",
        "fab fa-grav",
        "fab fa-gripfire",
        "fab fa-grunt",
        "fab fa-gulp",
        "fas fa-h-square",
        "fab fa-hacker-news",
        "fab fa-hacker-news-square",
        "fas fa-hand-lizard", 
        "far fa-hand-lizard",
        "fas fa-hand-paper", 
        "far fa-hand-paper",
        "fas fa-hand-peace", 
        "far fa-hand-peace",
        "fas fa-hand-point-down", 
        "far fa-hand-point-down",
        "fas fa-hand-point-left", 
        "far fa-hand-point-left",
        "fas fa-hand-point-right", 
        "far fa-hand-point-right",
        "fas fa-hand-point-up", 
        "far fa-hand-point-up",
        "fas fa-hand-pointer", 
        "far fa-hand-pointer",
        "fas fa-hand-rock", 
        "far fa-hand-rock",
        "fas fa-hand-scissors", 
        "far fa-hand-scissors",
        "fas fa-hand-spock", 
        "far fa-hand-spock",
        "fas fa-handshake", 
        "far fa-handshake",
        "fas fa-hashtag",
        "fas fa-hdd", 
        "far fa-hdd",
        "fas fa-heading",
        "fas fa-headphones",
        "fas fa-heart", 
        "far fa-heart",
        "fas fa-heartbeat",
        "fab fa-hire-a-helper",
        "fas fa-history",
        "fas fa-home",
        "fab fa-hooli",
        "fas fa-hospital", 
        "far fa-hospital",
        "fab fa-hotjar",
        "fas fa-hourglass", 
        "far fa-hourglass",
        "fas fa-hourglass-end",
        "fas fa-hourglass-half",
        "fas fa-hourglass-start",
        "fab fa-houzz",
        "fab fa-html5",
        "fab fa-hubspot",
        "fas fa-i-cursor",
        "fas fa-id-badge", 
        "far fa-id-badge",
        "fas fa-id-card", 
        "far fa-id-card",
        "fas fa-image", 
        "far fa-image",
        "fas fa-images", 
        "far fa-images",
        "fab fa-imdb",
        "fas fa-inbox",
        "fas fa-indent",
        "fas fa-industry",
        "fas fa-info",
        "fas fa-info-circle",
        "fab fa-instagram",
        "fab fa-internet-explorer",
        "fab fa-ioxhost",
        "fas fa-italic",
        "fab fa-itunes",
        "fab fa-itunes-note",
        "fab fa-jenkins",
        "fab fa-joget",
        "fab fa-joomla",
        "fab fa-js",
        "fab fa-js-square",
        "fab fa-jsfiddle",
        "fas fa-key",
        "fas fa-keyboard", 
        "far fa-keyboard",
        "fab fa-keycdn",
        "fab fa-kickstarter",
        "fab fa-kickstarter-k",
        "fas fa-language",
        "fas fa-laptop",
        "fab fa-laravel",
        "fab fa-lastfm",
        "fab fa-lastfm-square",
        "fas fa-leaf",
        "fab fa-leanpub",
        "fas fa-lemon", 
        "far fa-lemon",
        "fab fa-less",
        "fas fa-level-down-alt",
        "fas fa-level-up-alt",
        "fas fa-life-ring", 
        "far fa-life-ring",
        "fas fa-lightbulb", 
        "far fa-lightbulb",
        "fab fa-line",
        "fas fa-link",
        "fab fa-linkedin",
        "fab fa-linkedin-in",
        "fab fa-linode",
        "fab fa-linux",
        "fas fa-lira-sign",
        "fas fa-list",
        "fas fa-list-alt", 
        "far fa-list-alt",
        "fas fa-list-ol",
        "fas fa-list-ul",
        "fas fa-location-arrow",
        "fas fa-lock",
        "fas fa-lock-open",
        "fas fa-long-arrow-alt-down",
        "fas fa-long-arrow-alt-left",
        "fas fa-long-arrow-alt-right",
        "fas fa-long-arrow-alt-up",
        "fas fa-low-vision",
        "fab fa-lyft",
        "fab fa-magento",
        "fas fa-magic",
        "fas fa-magnet",
        "fas fa-male",
        "fas fa-map", 
        "far fa-map",
        "fas fa-map-marker",
        "fas fa-map-marker-alt",
        "fas fa-map-pin",
        "fas fa-map-signs",
        "fas fa-mars",
        "fas fa-mars-double",
        "fas fa-mars-stroke",
        "fas fa-mars-stroke-h",
        "fas fa-mars-stroke-v",
        "fab fa-maxcdn",
        "fab fa-medapps",
        "fab fa-medium",
        "fab fa-medium-m",
        "fas fa-medkit",
        "fab fa-medrt",
        "fab fa-meetup",
        "fas fa-meh", 
        "far fa-meh",
        "fas fa-mercury",
        "fas fa-microchip",
        "fas fa-microphone",
        "fas fa-microphone-slash",
        "fab fa-microsoft",
        "fas fa-minus",
        "fas fa-minus-circle",
        "fas fa-minus-square", 
        "far fa-minus-square",
        "fab fa-mix",
        "fab fa-mixcloud",
        "fab fa-mizuni",
        "fas fa-mobile",
        "fas fa-mobile-alt",
        "fab fa-modx",
        "fab fa-monero",
        "fas fa-money-bill-alt", 
        "far fa-money-bill-alt",
        "fas fa-moon", 
        "far fa-moon",
        "fas fa-motorcycle",
        "fas fa-mouse-pointer",
        "fas fa-music",
        "fab fa-napster",
        "fas fa-neuter",
        "fas fa-newspaper", 
        "far fa-newspaper",
        "fab fa-nintendo-switch",
        "fab fa-node",
        "fab fa-node-js",
        "fab fa-npm",
        "fab fa-ns8",
        "fab fa-nutritionix",
        "fas fa-object-group", 
        "far fa-object-group",
        "fas fa-object-ungroup", 
        "far fa-object-ungroup",
        "fab fa-odnoklassniki",
        "fab fa-odnoklassniki-square",
        "fab fa-opencart",
        "fab fa-openid",
        "fab fa-opera",
        "fab fa-optin-monster",
        "fab fa-osi",
        "fas fa-outdent",
        "fab fa-page4",
        "fab fa-pagelines",
        "fas fa-paint-brush",
        "fab fa-palfed",
        "fas fa-paper-plane", 
        "far fa-paper-plane",
        "fas fa-paperclip",
        "fas fa-paragraph",
        "fas fa-paste",
        "fab fa-patreon",
        "fas fa-pause",
        "fas fa-pause-circle", 
        "far fa-pause-circle",
        "fas fa-paw",
        "fab fa-paypal",
        "fas fa-pen-square",
        "fas fa-pencil-alt",
        "fas fa-percent",
        "fab fa-periscope",
        "fab fa-phabricator",
        "fab fa-phoenix-framework",
        "fas fa-phone",
        "fas fa-phone-square",
        "fas fa-phone-volume",
        "fab fa-pied-piper",
        "fab fa-pied-piper-alt",
        "fab fa-pied-piper-pp",
        "fab fa-pinterest",
        "fab fa-pinterest-p",
        "fab fa-pinterest-square",
        "fas fa-plane",
        "fas fa-play",
        "fas fa-play-circle", 
        "far fa-play-circle",
        "fab fa-playstation",
        "fas fa-plug",
        "fas fa-plus",
        "fas fa-plus-circle",
        "fas fa-plus-square", 
        "far fa-plus-square",
        "fas fa-podcast",
        "fas fa-pound-sign",
        "fas fa-power-off",
        "fas fa-print",
        "fab fa-product-hunt",
        "fab fa-pushed",
        "fas fa-puzzle-piece",
        "fab fa-python",
        "fab fa-qq",
        "fas fa-qrcode",
        "fas fa-question",
        "fas fa-question-circle", 
        "far fa-question-circle",
        "fab fa-quora",
        "fas fa-quote-left",
        "fas fa-quote-right",
        "fas fa-random",
        "fab fa-ravelry",
        "fab fa-react",
        "fab fa-rebel",
        "fas fa-recycle",
        "fab fa-red-river",
        "fab fa-reddit",
        "fab fa-reddit-alien",
        "fab fa-reddit-square",
        "fas fa-redo",
        "fas fa-redo-alt",
        "fas fa-registered", 
        "far fa-registered",
        "fab fa-rendact",
        "fab fa-renren",
        "fas fa-reply",
        "fas fa-reply-all",
        "fab fa-replyd",
        "fab fa-resolving",
        "fas fa-retweet",
        "fas fa-road",
        "fas fa-rocket",
        "fab fa-rocketchat",
        "fab fa-rockrms",
        "fas fa-rss",
        "fas fa-rss-square",
        "fas fa-ruble-sign",
        "fas fa-rupee-sign",
        "fab fa-safari",
        "fab fa-sass",
        "fas fa-save", 
        "far fa-save",
        "fab fa-schlix",
        "fab fa-scribd",
        "fas fa-search",
        "fas fa-search-minus",
        "fas fa-search-plus",
        "fab fa-searchengin",
        "fab fa-sellcast",
        "fab fa-sellsy",
        "fas fa-server",
        "fab fa-servicestack",
        "fas fa-share",
        "fas fa-share-alt",
        "fas fa-share-alt-square",
        "fas fa-share-square", 
        "far fa-share-square",
        "fas fa-shekel-sign",
        "fas fa-shield-alt",
        "fas fa-ship",
        "fab fa-shirtsinbulk",
        "fas fa-shopping-bag",
        "fas fa-shopping-basket",
        "fas fa-shopping-cart",
        "fas fa-shower",
        "fas fa-sign-in-alt",
        "fas fa-sign-language",
        "fas fa-sign-out-alt",
        "fas fa-signal",
        "fab fa-simplybuilt",
        "fab fa-sistrix",
        "fas fa-sitemap",
        "fab fa-skyatlas",
        "fab fa-skype",
        "fab fa-slack",
        "fab fa-slack-hash",
        "fas fa-sliders-h",
        "fab fa-slideshare",
        "fas fa-smile", 
        "far fa-smile",
        "fab fa-snapchat",
        "fab fa-snapchat-ghost",
        "fab fa-snapchat-square",
        "fas fa-snowflake", 
        "far fa-snowflake",
        "fas fa-sort",
        "fas fa-sort-alpha-down",
        "fas fa-sort-alpha-up",
        "fas fa-sort-amount-down",
        "fas fa-sort-amount-up",
        "fas fa-sort-down",
        "fas fa-sort-numeric-down",
        "fas fa-sort-numeric-up",
        "fas fa-sort-up",
        "fab fa-soundcloud",
        "fas fa-space-shuttle",
        "fab fa-speakap",
        "fas fa-spinner",
        "fab fa-spotify",
        "fas fa-square", 
        "far fa-square",
        "fab fa-stack-exchange",
        "fab fa-stack-overflow",
        "fas fa-star", 
        "far fa-star",
        "fas fa-star-half", 
        "far fa-star-half",
        "fab fa-staylinked",
        "fab fa-steam",
        "fab fa-steam-square",
        "fab fa-steam-symbol",
        "fas fa-step-backward",
        "fas fa-step-forward",
        "fas fa-stethoscope",
        "fab fa-sticker-mule",
        "fas fa-sticky-note", 
        "far fa-sticky-note",
        "fas fa-stop",
        "fas fa-stop-circle", 
        "far fa-stop-circle",
        "fab fa-strava",
        "fas fa-street-view",
        "fas fa-strikethrough",
        "fab fa-stripe",
        "fab fa-stripe-s",
        "fab fa-studiovinari",
        "fab fa-stumbleupon",
        "fab fa-stumbleupon-circle",
        "fas fa-subscript",
        "fas fa-subway",
        "fas fa-suitcase",
        "fas fa-sun", 
        "far fa-sun",
        "fab fa-superpowers",
        "fas fa-superscript",
        "fab fa-supple",
        "fas fa-sync",
        "fas fa-sync-alt",
        "fas fa-table",
        "fas fa-tablet",
        "fas fa-tablet-alt",
        "fas fa-tachometer-alt",
        "fas fa-tag",
        "fas fa-tags",
        "fas fa-tasks",
        "fas fa-taxi",
        "fab fa-telegram",
        "fab fa-telegram-plane",
        "fab fa-tencent-weibo",
        "fas fa-terminal",
        "fas fa-text-height",
        "fas fa-text-width",
        "fas fa-th",
        "fas fa-th-large",
        "fas fa-th-list",
        "fab fa-themeisle",
        "fas fa-thermometer-empty",
        "fas fa-thermometer-full",
        "fas fa-thermometer-half",
        "fas fa-thermometer-quarter",
        "fas fa-thermometer-three-quarters",
        "fas fa-thumbs-down", 
        "far fa-thumbs-down",
        "fas fa-thumbs-up", 
        "far fa-thumbs-up",
        "fas fa-thumbtack",
        "fas fa-ticket-alt",
        "fas fa-times",
        "fas fa-times-circle", 
        "far fa-times-circle",
        "fas fa-tint",
        "fas fa-toggle-off",
        "fas fa-toggle-on",
        "fas fa-trademark",
        "fas fa-train",
        "fas fa-transgender",
        "fas fa-transgender-alt",
        "fas fa-trash",
        "fas fa-trash-alt", 
        "far fa-trash-alt",
        "fas fa-tree",
        "fab fa-trello",
        "fab fa-tripadvisor",
        "fas fa-trophy",
        "fas fa-truck",
        "fas fa-tty",
        "fab fa-tumblr",
        "fab fa-tumblr-square",
        "fas fa-tv",
        "fab fa-twitch",
        "fab fa-twitter",
        "fab fa-twitter-square",
        "fab fa-typo3",
        "fab fa-uber",
        "fab fa-uikit",
        "fas fa-umbrella",
        "fas fa-underline",
        "fas fa-undo",
        "fas fa-undo-alt",
        "fab fa-uniregistry",
        "fas fa-universal-access",
        "fas fa-university",
        "fas fa-unlink",
        "fas fa-unlock",
        "fas fa-unlock-alt",
        "fab fa-untappd",
        "fas fa-upload",
        "fab fa-usb",
        "fas fa-user", 
        "far fa-user",
        "fas fa-user-circle", 
        "far fa-user-circle",
        "fas fa-user-md",
        "fas fa-user-plus",
        "fas fa-user-secret",
        "fas fa-user-times",
        "fas fa-users",
        "fab fa-ussunnah",
        "fas fa-utensil-spoon",
        "fas fa-utensils",
        "fab fa-vaadin",
        "fas fa-venus",
        "fas fa-venus-double",
        "fas fa-venus-mars",
        "fab fa-viacoin",
        "fab fa-viadeo",
        "fab fa-viadeo-square",
        "fab fa-viber",
        "fas fa-video",
        "fab fa-vimeo",
        "fab fa-vimeo-square",
        "fab fa-vimeo-v",
        "fab fa-vine",
        "fab fa-vk",
        "fab fa-vnv",
        "fas fa-volume-down",
        "fas fa-volume-off",
        "fas fa-volume-up",
        "fab fa-vuejs",
        "fab fa-weibo",
        "fab fa-weixin",
        "fab fa-whatsapp",
        "fab fa-whatsapp-square",
        "fas fa-wheelchair",
        "fab fa-whmcs",
        "fas fa-wifi",
        "fab fa-wikipedia-w",
        "fas fa-window-close", 
        "far fa-window-close",
        "fas fa-window-maximize", 
        "far fa-window-maximize",
        "fas fa-window-minimize",
        "fas fa-window-restore", 
        "far fa-window-restore",
        "fab fa-windows",
        "fas fa-won-sign",
        "fab fa-wordpress",
        "fab fa-wordpress-simple",
        "fab fa-wpbeginner",
        "fab fa-wpexplorer",
        "fab fa-wpforms",
        "fas fa-wrench",
        "fab fa-xbox",
        "fab fa-xing",
        "fab fa-xing-square",
        "fab fa-y-combinator",
        "fab fa-yahoo",
        "fab fa-yandex",
        "fab fa-yandex-international",
        "fab fa-yelp",
        "fas fa-yen-sign",
        "fab fa-yoast",
        "fab fa-youtube");
    }
}?> 