<?php
/**
 * Resort Vacation functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package resort_vacation
 */

$resort_vacation_theme_data = wp_get_theme();
if( ! defined( 'RESORT_VACATION_THEME_VERSION' ) ) define ( 'RESORT_VACATION_THEME_VERSION', $resort_vacation_theme_data->get( 'Version' ) );
if( ! defined( 'RESORT_VACATION_THEME_NAME' ) ) define( 'RESORT_VACATION_THEME_NAME', $resort_vacation_theme_data->get( 'Name' ) );

if ( ! function_exists( 'resort_vacation_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function resort_vacation_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary1' => esc_html__( 'Primary Left', 'resort-vacation' ),
		'primary2' => esc_html__( 'Primary Right', 'resort-vacation' ),
		'primary-mobile' => esc_html__( 'Primary Mobile Media', 'resort-vacation' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
        'status',
        'audio', 
        'chat'
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'resort_vacation_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );


	/* Custom Logo */
    add_theme_support( 'custom-logo', array(    	
    	'header-text' => array( 'site-title', 'site-description' ),
    ) );

    load_theme_textdomain( 'resort-vacation', get_template_directory() . '/languages' );
}
endif;
add_action( 'after_setup_theme', 'resort_vacation_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $resort_vacation_content_width
 */
function resort_vacation_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'resort_vacation_content_width', 780 );
}
add_action( 'after_setup_theme', 'resort_vacation_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function resort_vacation_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'resort-vacation' ),
		'id'            => 'right-sidebar',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer One', 'resort-vacation' ),
		'id'            => 'footer-one',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Two', 'resort-vacation' ),
		'id'            => 'footer-two',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Three', 'resort-vacation' ),
		'id'            => 'footer-three',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Four', 'resort-vacation' ),
		'id'            => 'footer-four',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'resort_vacation_widgets_init' );

if( ! function_exists( 'resort_vacation_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function resort_vacation_scripts() {

	// Use minified libraries if SCRIPT_DEBUG is false
    $resort_vacation_build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $resort_vacation_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/css/build/bootstrap.css' );
    wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/css/build/owl.carousel.css' );

	wp_enqueue_style( 'resort-vacation-style', get_stylesheet_uri(), array(), RESORT_VACATION_THEME_VERSION );

	if( resort_vacation_woocommerce_activated() ) 
    wp_enqueue_style( 'resort-vacation-woocommerce-style', get_template_directory_uri(). '/css' . $resort_vacation_build . '/woocommerce' . $resort_vacation_suffix . '.css', array('resort-vacation-style'), RESORT_VACATION_THEME_VERSION );
	
  	wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $resort_vacation_build . '/all' . $resort_vacation_suffix . '.js', array( 'jquery' ), '6.1.1', true );
  	wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $resort_vacation_build . '/v4-shims' . $resort_vacation_suffix . '.js', array( 'jquery' ), '6.1.1', true );
  	wp_enqueue_script( 'resort-vacation-modal-accessibility', get_template_directory_uri() . '/js' . $resort_vacation_build . '/modal-accessibility' . $resort_vacation_suffix . '.js', array( 'jquery' ), RESORT_VACATION_THEME_VERSION, true );
	wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/js/build/owl.carousel.js', array('jquery'), '2.6.0', true );
	wp_enqueue_script( 'resort-vacation-js', get_template_directory_uri() . '/js/build/custom.js', array('jquery'), RESORT_VACATION_THEME_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'resort_vacation_scripts' );

if( ! function_exists( 'resort_vacation_admin_scripts' ) ) :
/**
 * Addmin scripts
*/
function resort_vacation_admin_scripts() {
	wp_enqueue_style( 'resort-vacation-admin-style',get_template_directory_uri().'/inc/css/admin.css', RESORT_VACATION_THEME_VERSION, 'screen' );
}
endif;
add_action( 'admin_enqueue_scripts', 'resort_vacation_admin_scripts' );

function resort_vacation_customize_enque_js(){
	wp_enqueue_script( 'customizer', get_template_directory_uri() . '/inc/js/customizer.js', array('jquery'), '2.6.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'resort_vacation_customize_enque_js', 0 );


if( ! function_exists( 'resort_vacation_block_editor_styles' ) ) :
/**
 * Enqueue editor styles for Gutenberg
 */
function resort_vacation_block_editor_styles() {
	// Use minified libraries if SCRIPT_DEBUG is false
	$resort_vacation_build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
	$resort_vacation_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	
	// Block styles.
	wp_enqueue_style( 'resort-vacation-block-editor-style', get_template_directory_uri() . '/css' . $resort_vacation_build . '/editor-block' . $resort_vacation_suffix . '.css' );
}
endif;
add_action( 'enqueue_block_editor_assets', 'resort_vacation_block_editor_styles' );


if ( ! function_exists( 'resort_vacation_sanitize_checkbox' ) ) :

	/**
	 * Sanitize checkbox.
	 *
	 * @since 1.0.0
	 *	
	 */
	function resort_vacation_sanitize_checkbox( $checked ) {

		return ( ( isset( $checked ) && true === $checked ) ? true : false );

	}

endif;

/**
 * Sanitize number range.
 *	
 */
function resort_vacation_sanitize_number_range( $input, $setting ) {

	// Ensure input is an absolute integer.
	$input = absint( $input );

	// Get the input attributes associated with the setting.
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;

	// Get min.
	$min = ( isset( $atts['min'] ) ? $atts['min'] : $input );

	// Get max.
	$max = ( isset( $atts['max'] ) ? $atts['max'] : $input );

	// Get Step.
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

	// If the input is within the valid range, return it; otherwise, return the default.
	return ( $min <= $input && $input <= $max && is_int( $input / $step ) ? $input : $setting->default );

}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extra.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Social Links Widget
 */
require get_template_directory() . '/inc/widget-social-links.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Info Theme
 */
require get_template_directory() . '/inc/info.php';

/**
 * Load plugin for right and no sidebar
 */
if( resort_vacation_woocommerce_activated() ) {
	require get_template_directory() . '/inc/woocommerce-functions.php';
}

/**
 * Getting Started
*/
require get_template_directory() . '/inc/getting-started/getting-started.php';


/**
 * Remove header text setting and control from the Customizer.
 */
function resort_vacation_remove_customizer_setting($wp_customize) {
    // Replace 'your_setting_id' with the actual ID or name of the setting you want to remove
    $wp_customize->remove_control('display_header_text');
    $wp_customize->remove_setting('display_header_text');
}
add_action('customize_register', 'resort_vacation_remove_customizer_setting');

function resort_vacation_customizer_css() {
    ?>
    <style type="text/css">
        .main-navigation a {
            font-weight: <?php echo esc_html( get_theme_mod( 'menu_font_weight', '500' ) ); ?>;
            text-transform: <?php echo esc_html( get_theme_mod( 'menu_text_transform', 'capitalize' ) ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'resort_vacation_customizer_css' );


// Sanitize Font Weight
function resort_vacation_sanitize_font_weight( $value ) {
    $valid = array( '100', '200', '300', '400', '500', '600', '700', '800', '900' );
    return in_array( $value, $valid ) ? $value : '400';
}

// Sanitize Text Transform
function resort_vacation_sanitize_text_transform( $value ) {
    $valid = array( 'none', 'capitalize', 'uppercase', 'lowercase' );
    return in_array( $value, $valid ) ? $value : 'none';
}

// Retrieve the slider visibility setting
$resort_vacation_slider = get_theme_mod('resort_vacation_slider_setting', false);

// Function to output custom CSS directly in the head section
function resort_vacation_custom_css() {
    global $resort_vacation_slider;
    if ($resort_vacation_slider == false) {
        echo '<style type="text/css">
            .page-template-template-home header#masthead {
                position: static;
                background:#fc771c;
            }
        </style>';
    }
}

// Hook the function into the wp_head action
add_action('wp_head', 'resort_vacation_custom_css');

function resort_vacation_custom_blog_banner_title() {
    if (is_404()) {
        echo '<h1 class="entry-title">'. esc_html( 'Comments are closed.', 'resort-vacation' ).'</h1>';
    } elseif (is_search()) {
        echo '<h1 class="entry-title">'. esc_html( 'Search Result For.', 'resort-vacation' ).' ' . get_search_query() . '</h1>';
    } elseif (is_home() && !is_front_page()) {
        echo '<h1 class="entry-title">'. esc_html( 'Blogs', 'resort-vacation' ).'</h1>';
    } elseif (function_exists('is_shop') && is_shop()) {
        echo '<h1 class="entry-title">'. esc_html( 'Shop', 'resort-vacation' ).'</h1>';
    } elseif (is_page_template('template-homepage.php')) {
    } elseif (is_page()) {
        the_title('<h1 class="entry-title">', '</h1>');
    } elseif (is_single()) {
        the_title('<h1 class="entry-title">', '</h1>');
    } elseif (is_archive()) {
        the_archive_title('<h1 class="entry-title">', '</h1>');
    } else {
        the_archive_title('<h1 class="entry-title">', '</h1>');
    }
}

function resort_vacation_hide_single_header_img() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            if (document.body.classList.contains('page-template-template-home')) {
                var headerImg = document.querySelector('.single-header-img');
                if (headerImg) {
                    headerImg.style.display = 'none';
                }
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'resort_vacation_hide_single_header_img');

/**
 * Display the admin notice unless dismissed.
 */
function resort_vacation_dashboard_notice() {
    // Check if the notice is dismissed
    $dismissed = get_user_meta(get_current_user_id(), 'resort_vacation_dismissable_notice', true);

    // Display the notice only if not dismissed
    if (!$dismissed) {
        ?>
        <div class="updated notice notice-success is-dismissible notice-get-started-class" data-notice="get-start" style="display: flex;padding: 10px;">
            <p><?php echo esc_html('Clicking the "Getting Started" button will launch your theme discovery.', 'resort-vacation'); ?></p>
            <a style="margin-left: 30px; padding: 8px 15px;" class="button button-primary"
               href="<?php echo esc_url(admin_url('themes.php?page=resort-vacation')); ?>"><?php esc_html_e('Getting Started', 'resort-vacation') ?></a>
           <a style="margin-left: 30px; padding: 8px 15px;" class="button button-primary"
           target="_blank" href="<?php echo esc_url('https://demo.themeignite.com/documentation/resort-vacation-free'); ?>"><?php esc_html_e('Free Documentation', 'resort-vacation') ?></a>
           
        </div>
        <?php
    }
}

// Hook to display the notice
add_action('admin_notices', 'resort_vacation_dashboard_notice');

/**
 * AJAX handler to dismiss the notice.
 */
function resort_vacation_dismissable_notice() {
    // Set user meta to indicate the notice is dismissed
    update_user_meta(get_current_user_id(), 'resort_vacation_dismissable_notice', true);
    die();
}

// Hook for the AJAX action
add_action('wp_ajax_resort_vacation_dismissable_notice', 'resort_vacation_dismissable_notice');

/**
 * Clear dismissed notice state when switching themes.
 */
function resort_vacation_switch_theme() {
    // Clear the dismissed notice state when switching themes
    delete_user_meta(get_current_user_id(), 'resort_vacation_dismissable_notice');
}

// Hook for switching themes
add_action('after_switch_theme', 'resort_vacation_switch_theme');

function resort_vacation_enqueue_google_fontss() {
    $resort_vacation_heading_font_family = get_theme_mod('resort_vacation_heading_font_family', '');
    $resort_vacation_body_font_family = get_theme_mod('resort_vacation_body_font_family', '');

    // Google Fonts URL builder
    $google_fonts = array(
        'Arial'          => '',
        'Verdana'        => '',
        'Helvetica'      => '',
        'Times New Roman'=> '',
        'Georgia'        => '',
        'Courier New'    => '',
        'Trebuchet MS'   => '',
        'Tahoma'         => '',
        'Palatino'       => '',
        'Garamond'       => '',
        'Impact'         => '',
        'Comic Sans MS'  => '',
        'Lucida Sans'    => '',
        'Arial Black'    => '',
        'Gill Sans'      => '',
        'Segoe UI'       => '',
        'Open Sans'      => 'Open+Sans:wght@400;700',
        'Roboto'         => 'Roboto:wght@400;700',
        'Lato'           => 'Lato:wght@400;700',
        'Montserrat'     => 'Montserrat:wght@400;700',
        'Libre Baskerville' => 'Libre+Baskerville:wght@400;700'
    );

    $resort_vacation_google_fonts_url = '';

    if (!empty($google_fonts[$resort_vacation_heading_font_family]) || !empty($google_fonts[$resort_vacation_body_font_family])) {
        $fonts = array();

        if (!empty($google_fonts[$resort_vacation_heading_font_family])) {
            $fonts[] = $google_fonts[$resort_vacation_heading_font_family];
        }

        if (!empty($google_fonts[$resort_vacation_body_font_family])) {
            $fonts[] = $google_fonts[$resort_vacation_body_font_family];
        }

        // Build Google Fonts URL
        $resort_vacation_google_fonts_url = add_query_arg(
            'family',
            implode('|', $fonts),
            'https://fonts.googleapis.com/css2'
        );
    }

    if ($resort_vacation_google_fonts_url) {
        wp_enqueue_style('resort-vacation-google-fonts', $resort_vacation_google_fonts_url, false);
    }
}
add_action('wp_enqueue_scripts', 'resort_vacation_enqueue_google_fontss');


function resort_vacation_apply_typography() {
    // Get the font family settings from the customizer
    $resort_vacation_heading_font_family = get_theme_mod('resort_vacation_heading_font_family');
    $resort_vacation_body_font_family = get_theme_mod('resort_vacation_body_font_family');

    // Only output CSS if one or both fonts are set
    if ($resort_vacation_body_font_family || $resort_vacation_heading_font_family) {
        ?>
        <style type="text/css">
            <?php if ($resort_vacation_body_font_family): ?>
            body, a, a:active, a:hover {
                font-family: <?php echo esc_html($resort_vacation_body_font_family); ?> !important;
            }
            <?php endif; ?>

            <?php if ($resort_vacation_heading_font_family): ?>
            h1, h2, h3, h4, h5, h6 {
                font-family: <?php echo esc_html($resort_vacation_heading_font_family); ?> !important;
            }
            <?php endif; ?>
        </style>
        <?php
    }
}
add_action('wp_head', 'resort_vacation_apply_typography');