<?php
/**
 * Resort Vacation Theme Customizer.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package resort_vacation
 */

if( ! function_exists( 'resort_vacation_customize_register' ) ):  
/**
 * Add postMessage support for site title and description for the Theme Customizer.F
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function resort_vacation_customize_register( $wp_customize ) {
    if ( version_compare( get_bloginfo('version'),'4.9', '>=') ) {
        $wp_customize->get_section( 'static_front_page' )->title = __( 'Static Front Page', 'resort-vacation' );
    }
	
    /* Option list of all post */	
    $resort_vacation_options_posts = array();
    $resort_vacation_options_posts_obj = get_posts('posts_per_page=-1');
    $resort_vacation_options_posts[''] = esc_html__( 'Choose Post', 'resort-vacation' );
    foreach ( $resort_vacation_options_posts_obj as $resort_vacation_posts ) {
    	$resort_vacation_options_posts[$resort_vacation_posts->ID] = $resort_vacation_posts->post_title;
    }
    
    /* Option list of all categories */
    $resort_vacation_args = array(
	   'type'                     => 'post',
	   'orderby'                  => 'name',
	   'order'                    => 'ASC',
	   'hide_empty'               => 1,
	   'hierarchical'             => 1,
	   'taxonomy'                 => 'category'
    ); 
    $resort_vacation_option_categories = array();
    $resort_vacation_category_lists = get_categories( $resort_vacation_args );
    $resort_vacation_option_categories[''] = esc_html__( 'Choose Category', 'resort-vacation' );
    foreach( $resort_vacation_category_lists as $resort_vacation_category ){
        $resort_vacation_option_categories[$resort_vacation_category->term_id] = $resort_vacation_category->name;
    }
    
    /** Default Settings */    
    $wp_customize->add_panel( 
        'wp_default_panel',
         array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => esc_html__( 'Default Settings', 'resort-vacation' ),
            'description' => esc_html__( 'Default section provided by wordpress customizer.', 'resort-vacation' ),
        ) 
    );
    
    $wp_customize->get_section( 'title_tagline' )->panel                  = 'wp_default_panel';
    $wp_customize->get_section( 'colors' )->panel                         = 'wp_default_panel';
    $wp_customize->get_section( 'header_image' )->panel                   = 'wp_default_panel';
    $wp_customize->get_section( 'background_image' )->panel               = 'wp_default_panel';
    $wp_customize->get_section( 'static_front_page' )->panel              = 'wp_default_panel';
    
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    
    /** Default Settings Ends */

     /** Site Title control */
    $wp_customize->add_setting( 
        'header_site_title', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'header_site_title',
        array(
            'label'       => __( 'Show / Hide Site Title', 'resort-vacation' ),
            'section'     => 'title_tagline',
            'type'        => 'checkbox',
        )
    );

    /** Tagline control */
    $wp_customize->add_setting( 
        'header_tagline', 
        array(
            'default'           => false,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'header_tagline',
        array(
            'label'       => __( 'Show / Hide Tagline', 'resort-vacation' ),
            'section'     => 'title_tagline',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting('logo_width', array(
        'sanitize_callback' => 'absint', 
    ));

    // Add a control for logo width
    $wp_customize->add_control('logo_width', array(
        'label' => __('Logo Width', 'resort-vacation'),
        'section' => 'title_tagline',
        'type' => 'number',
        'input_attrs' => array(
            'min' => '50', 
            'max' => '500', 
            'step' => '5', 
    ),
        'default' => '100', 
    ));


    /** Post Settings */
    $wp_customize->add_section(
        'resort_vacation_post_settings',
        array(
            'title' => esc_html__( 'Post Settings', 'resort-vacation' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
        )
    );

    // Excerpt Length
    $wp_customize->add_section(
        'resort_vacation_post_excerpt_length', 
        array(    
            'title'       => __('Excerpt Length', 'resort-vacation'),
            'panel'       => 'theme_option_panel'    
        )
    );

    $wp_customize->add_setting( 
        'resort_vacation_post_excerpt_length', 
        array(
            'default'           => '20',
            'sanitize_callback' => 'resort_vacation_sanitize_number_range',
    ) );
    $wp_customize->add_control( 
        'resort_vacation_post_excerpt_length', 
        array(
            'label'       => esc_html__( 'Excerpt Length', 'resort-vacation' ),
            'section'     => 'resort_vacation_post_settings',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 1, 'max' => 200, 'style' => 'width: 100%;' ),
    ) );

    /** Post Heading control */
    $wp_customize->add_setting( 
        'resort_vacation_post_heading_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_post_heading_setting',
        array(
            'label'       => __( 'Show / Hide Post Heading', 'resort-vacation' ),
            'section'     => 'resort_vacation_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Post Meta control */
    $wp_customize->add_setting( 
        'resort_vacation_post_meta_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_post_meta_setting',
        array(
            'label'       => __( 'Show / Hide Post Meta', 'resort-vacation' ),
            'section'     => 'resort_vacation_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Post Image control */
    $wp_customize->add_setting( 
        'resort_vacation_post_image_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_post_image_setting',
        array(
            'label'       => __( 'Show / Hide Post Image', 'resort-vacation' ),
            'section'     => 'resort_vacation_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Post Content control */
    $wp_customize->add_setting( 
        'resort_vacation_post_content_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_post_content_setting',
        array(
            'label'       => __( 'Show / Hide Post Content', 'resort-vacation' ),
            'section'     => 'resort_vacation_post_settings',
            'type'        => 'checkbox',
        )
    );
    
    // Add setting for Read More button
    $wp_customize->add_setting( 'resort_vacation_readmore_setting', array(
        'default'           => true, // Default value (true for showing Read More button)
        'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
    ) );

    // Add control for Read More button
    $wp_customize->add_control( 'resort_vacation_readmore_setting', array(
        'label'    => esc_html__( 'Show Read More Button', 'resort-vacation' ),
        'section'  => 'resort_vacation_post_settings',
        'type'        => 'checkbox',
    ) );

    /** Post Settings Ends */

    /** Single Post Settings */
    $wp_customize->add_section(
        'resort_vacation_single_post_settings',
        array(
            'title' => esc_html__( 'Single Post Settings', 'resort-vacation' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
        )
    );

    /** Single Post Meta control */
    $wp_customize->add_setting( 
        'resort_vacation_single_post_meta_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_single_post_meta_setting',
        array(
            'label'       => __( 'Show / Hide Single Post Meta', 'resort-vacation' ),
            'section'     => 'resort_vacation_single_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Single Post Content control */
    $wp_customize->add_setting( 
        'resort_vacation_single_post_content_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_single_post_content_setting',
        array(
            'label'       => __( 'Show / Hide Single Post Content', 'resort-vacation' ),
            'section'     => 'resort_vacation_single_post_settings',
            'type'        => 'checkbox',
        )
    );


    /** Single Post Settings Ends */

    // Typography Settings Section
    $wp_customize->add_section('resort_vacation_typography_settings', array(
        'title'      => esc_html__('Typography Settings', 'resort-vacation'),
        'priority'   => 30,
        'capability' => 'edit_theme_options',
    ));

    // Array of fonts to choose from
    $font_choices = array(
        ''               => __('Select', 'resort-vacation'),
        'Arial'          => 'Arial, sans-serif',
        'Verdana'        => 'Verdana, sans-serif',
        'Helvetica'      => 'Helvetica, sans-serif',
        'Times New Roman'=> '"Times New Roman", serif',
        'Georgia'        => 'Georgia, serif',
        'Courier New'    => '"Courier New", monospace',
        'Trebuchet MS'   => '"Trebuchet MS", sans-serif',
        'Tahoma'         => 'Tahoma, sans-serif',
        'Palatino'       => '"Palatino Linotype", serif',
        'Garamond'       => 'Garamond, serif',
        'Impact'         => 'Impact, sans-serif',
        'Comic Sans MS'  => '"Comic Sans MS", cursive, sans-serif',
        'Lucida Sans'    => '"Lucida Sans Unicode", sans-serif',
        'Arial Black'    => '"Arial Black", sans-serif',
        'Gill Sans'      => '"Gill Sans", sans-serif',
        'Segoe UI'       => '"Segoe UI", sans-serif',
        'Open Sans'      => '"Open Sans", sans-serif',
        'Roboto'         => 'Roboto, sans-serif',
        'Lato'           => 'Lato, sans-serif',
        'Montserrat'     => 'Montserrat, sans-serif',
        'Libre Baskerville' => 'Libre Baskerville',
    );

    // Heading Font Setting
    $wp_customize->add_setting('resort_vacation_heading_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'resort_vacation_sanitize_choicess',
    ));
    $wp_customize->add_control('resort_vacation_heading_font_family', array(
        'type'    => 'select',
        'choices' => $font_choices,
        'label'   => __('Select Font for Heading', 'resort-vacation'),
        'section' => 'resort_vacation_typography_settings',
    ));

    // Body Font Setting
    $wp_customize->add_setting('resort_vacation_body_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'resort_vacation_sanitize_choicess',
    ));
    $wp_customize->add_control('resort_vacation_body_font_family', array(
        'type'    => 'select',
        'choices' => $font_choices,
        'label'   => __('Select Font for Body', 'resort-vacation'),
        'section' => 'resort_vacation_typography_settings',
    ));

    /** Typography Settings Section End */

    /** General Settings */
    $wp_customize->add_section(
        'resort_vacation_general_settings',
        array(
            'title' => esc_html__( 'General Settings', 'resort-vacation' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
        )
    );

    /** Preloader control */
    $wp_customize->add_setting( 
        'resort_vacation_preloader_setting', 
        array(
            'default'           => false,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_preloader_setting',
        array(
            'label'       => __( 'Show / Hide Preloader', 'resort-vacation' ),
            'section'     => 'resort_vacation_general_settings',
            'type'        => 'checkbox',
        )
    );

    /** Scroll To Top control */
    $wp_customize->add_setting( 
        'resort_vacation_scroll_to_top', 
        array(
            'default'           => true,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_scroll_to_top',
        array(
            'label'       => __( 'Show / Hide Scroll To Top', 'resort-vacation' ),
            'section'     => 'resort_vacation_general_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting('resort_vacation_scroll_button_icon',array(
        'default'   => 'fas fa-arrow-up',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control(new Resort_Vacation_Changeable_Icon(
        $wp_customize,'resort_vacation_scroll_button_icon',array(
        'label' => __('Scroll Top Icon','resort-vacation'),
        'transport' => 'refresh',
        'section'   => 'resort_vacation_general_settings',
        'type'      => 'icon'
    )));

    // Add Setting for Menu Font Weight
    $wp_customize->add_setting( 'menu_font_weight', array(
        'default'           => '500',
        'sanitize_callback' => 'resort_vacation_sanitize_font_weight',
    ) );

    // Add Control for Menu Font Weight
    $wp_customize->add_control( 'menu_font_weight', array(
        'label'    => __( 'Menu Font Weight', 'resort-vacation' ),
        'section'  => 'resort_vacation_general_settings',
        'type'     => 'select',
        'choices'  => array(
            '100' => __( '100 - Thin', 'resort-vacation' ),
            '200' => __( '200 - Extra Light', 'resort-vacation' ),
            '300' => __( '300 - Light', 'resort-vacation' ),
            '400' => __( '400 - Normal', 'resort-vacation' ),
            '500' => __( '500 - Medium', 'resort-vacation' ),
            '600' => __( '600 - Semi Bold', 'resort-vacation' ),
            '700' => __( '700 - Bold', 'resort-vacation' ),
            '800' => __( '800 - Extra Bold', 'resort-vacation' ),
            '900' => __( '900 - Black', 'resort-vacation' ),
        ),
    ) );

    // Add Setting for Menu Text Transform
    $wp_customize->add_setting( 'menu_text_transform', array(
        'default'           => 'capitalize',
        'sanitize_callback' => 'resort_vacation_sanitize_text_transform',
    ) );

    // Add Control for Menu Text Transform
    $wp_customize->add_control( 'menu_text_transform', array(
        'label'    => __( 'Menu Text Transform', 'resort-vacation' ),
        'section'  => 'resort_vacation_general_settings',
        'type'     => 'select',
        'choices'  => array(
            'none'       => __( 'None', 'resort-vacation' ),
            'capitalize' => __( 'Capitalize', 'resort-vacation' ),
            'uppercase'  => __( 'Uppercase', 'resort-vacation' ),
            'lowercase'  => __( 'Lowercase', 'resort-vacation' ),
        ),
    ) );

    /** General Settings Ends */

    /** Header Section Settings */
    $wp_customize->add_section(
        'resort_vacation_header_section_settings',
        array(
            'title' => esc_html__( 'Header Section Settings', 'resort-vacation' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
        )
    );

    /** Header Section control */
    $wp_customize->add_setting( 
        'resort_vacation_header_setting', 
        array(
            'default' => false ,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_header_setting',
        array(
            'label'       => __( 'Show Header', 'resort-vacation' ),
            'section'     => 'resort_vacation_header_section_settings',
            'type'        => 'checkbox',
        )
    );

     /** Phone */
    $wp_customize->add_setting(
        'resort_vacation_header_phone',
        array( 
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_header_phone',
        array(
            'label' => esc_html__( 'Add Phone', 'resort-vacation' ),
            'section' => 'resort_vacation_header_section_settings',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting('resort_vacation_phone_icon',array(
        'default'   => 'fas fa-phone',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control(new Resort_Vacation_Changeable_Icon(
        $wp_customize,'resort_vacation_phone_icon',array(
        'label' => __('Phone Icon','resort-vacation'),
        'transport' => 'refresh',
        'section'   => 'resort_vacation_header_section_settings',
        'type'      => 'icon'
    )));

    $wp_customize->add_setting( 
        'resort_vacation_show_hide_search', 
        array(
            'default' => false ,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_show_hide_search',
        array(
            'label'       => __( 'Show Search Icon', 'resort-vacation' ),
            'section'     => 'resort_vacation_header_section_settings',
            'type'        => 'checkbox',
        )
    );
    $wp_customize->add_setting('resort_vacation_search_icon',array(
        'default'   => 'fas fa-search',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control(new Resort_Vacation_Changeable_Icon(
        $wp_customize,'resort_vacation_search_icon',array(
        'label' => __('Search Icon','resort-vacation'),
        'transport' => 'refresh',
        'section'   => 'resort_vacation_header_section_settings',
        'type'      => 'icon'
    )));


  /**  Sign Up URL */
    $wp_customize->add_setting(
        'resort_vacation_sign_url',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_sign_url',
        array(
            'label' => esc_html__( 'Add Sign Up URL', 'resort-vacation' ),
            'section' => 'resort_vacation_header_section_settings',
            'type' => 'url',
        )
    );

    $wp_customize->add_setting('resort_vacation_myaccount_icon',array(
        'default'   => 'fas fa-user',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control(new Resort_Vacation_Changeable_Icon(
        $wp_customize,'resort_vacation_myaccount_icon',array(
        'label' => __('Sign Up Icon','resort-vacation'),
        'transport' => 'refresh',
        'section'   => 'resort_vacation_header_section_settings',
        'type'      => 'icon'
    )));
   
    /** Header Section Settings End */

    /** Socail Section Settings */
    $wp_customize->add_section(
        'resort_vacation_social_section_settings',
        array(
            'title' => esc_html__( 'Social Media Section Settings', 'resort-vacation' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
        )
    );

    /** Socail Section control */
    $wp_customize->add_setting( 
        'resort_vacation_social_icon_setting', 
        array(
            'default' => false,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_social_icon_setting',
        array(
            'label'       => __( 'Show Social Icon', 'resort-vacation' ),
            'section'     => 'resort_vacation_social_section_settings',
            'type'        => 'checkbox',
        )
    );

    /**  Social Link 1 */
    $wp_customize->add_setting(
        'resort_vacation_social_link_1',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_social_link_1',
        array(
            'label' => esc_html__( 'Add Facebook Link', 'resort-vacation' ),
            'section' => 'resort_vacation_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 2 */
    $wp_customize->add_setting(
        'resort_vacation_social_link_2',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_social_link_2',
        array(
            'label' => esc_html__( 'Add Twitter Link', 'resort-vacation' ),
            'section' => 'resort_vacation_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 3 */
    $wp_customize->add_setting(
        'resort_vacation_social_link_3',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_social_link_3',
        array(
            'label' => esc_html__( 'Add Instagram Link', 'resort-vacation' ),
            'section' => 'resort_vacation_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 4 */
    $wp_customize->add_setting(
        'resort_vacation_social_link_4',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_social_link_4',
        array(
            'label' => esc_html__( 'Add Pintrest Link', 'resort-vacation' ),
            'section' => 'resort_vacation_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 4 */
    $wp_customize->add_setting(
        'resort_vacation_social_link_5',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_social_link_5',
        array(
            'label' => esc_html__( 'Add Youtube Link', 'resort-vacation' ),
            'section' => 'resort_vacation_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 4 */
    $wp_customize->add_setting(
        'resort_vacation_social_link_6',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_social_link_6',
        array(
            'label' => esc_html__( 'Add Linkedin Link', 'resort-vacation' ),
            'section' => 'resort_vacation_social_section_settings',
            'type' => 'url',
        )
    );

    /** Social Section Settings End */

    /** Home Page Settings */
    $wp_customize->add_panel( 
        'resort_vacation_home_page_settings',
         array(
            'priority' => 40,
            'capability' => 'edit_theme_options',
            'title' => esc_html__( 'Home Page Settings', 'resort-vacation' ),
            'description' => esc_html__( 'Customize Home Page Settings', 'resort-vacation' ),
        ) 
    );

    /** Slider Section Settings */
    $wp_customize->add_section(
        'resort_vacation_slider_section_settings',
        array(
            'title' => esc_html__( 'Slider Section Settings', 'resort-vacation' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'panel' => 'resort_vacation_home_page_settings',
        )
    );

    /** Slider Section control */
    $wp_customize->add_setting( 
        'resort_vacation_slider_setting', 
        array(
            'default' => false ,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_slider_setting',
        array(
            'label'       => __( 'Show Slider', 'resort-vacation' ),
            'section'     => 'resort_vacation_slider_section_settings',
            'type'        => 'checkbox',
        )
    );

    // Section Text
    $wp_customize->add_setting('resort_vacation_slider_text_extra', 
        array(
        'default'           => '',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',    
        'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('resort_vacation_slider_text_extra', 
        array(
        'label'       => __('Slider Extra Title', 'resort-vacation'),
        'section'     => 'resort_vacation_slider_section_settings',   
        'settings'    => 'resort_vacation_slider_text_extra',
        'type'        => 'text'
        )
    );
    
    $categories = get_categories();
        $cat_posts = array();
            $i = 0;
            $cat_posts[]='Select';
        foreach($categories as $category){
            if($i==0){
            $default = $category->slug;
            $i++;
        }
        $cat_posts[$category->slug] = $category->name;
    }

    $wp_customize->add_setting(
        'resort_vacation_blog_slide_category',
        array(
            'default'   => 'select',
            'sanitize_callback' => 'resort_vacation_sanitize_choices',
        )
    );
    $wp_customize->add_control(
        'resort_vacation_blog_slide_category',
        array(
            'type'    => 'select',
            'choices' => $cat_posts,
            'label' => __('Select Category to display Slider Post','resort-vacation'),
            'section' => 'resort_vacation_slider_section_settings',
        )
    );

    /** About Section Settings */
    
    $wp_customize->add_section( 'resort_vacation_section_featured_about',
        array(
        'title'      => __( 'Discovery Section', 'resort-vacation' ),
        'priority'   => 110,
        'capability' => 'edit_theme_options',
        'panel'      => 'resort_vacation_home_page_settings',
        )
    );

    /** Blog Section control */
    $wp_customize->add_setting( 
        'resort_vacation_about_setting', 
        array(
            'default' => false ,
            'sanitize_callback' => 'resort_vacation_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'resort_vacation_about_setting',
        array(
            'label'       => __( 'Show Blog', 'resort-vacation' ),
            'section'     => 'resort_vacation_section_featured_about',
            'type'        => 'checkbox',
        )
    );

    // Section Title
    $wp_customize->add_setting('resort_vacation_featured_mission_section_title', 
        array(
        'capability'        => 'edit_theme_options',    
        'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('resort_vacation_featured_mission_section_title', 
        array(
        'label'       => __('Section Title', 'resort-vacation'),
        'section'     => 'resort_vacation_section_featured_about',   
        'settings'    => 'resort_vacation_featured_mission_section_title',
        'type'        => 'text'
        )
    );

    // Post Categories
    $categories = get_categories();
    $cat_posts = array();
    $default = '';
    $cat_posts[] = 'Select';
    foreach ($categories as $category) {
        $cat_posts[$category->slug] = $category->name;
    }

    $wp_customize->add_setting(
        'resort_vacation_trending_post_slider_args_',
        array(
            'default'            => 'select',
            'sanitize_callback'  => 'resort_vacation_sanitize_choices',
        )
    );
    $wp_customize->add_control(
        'resort_vacation_trending_post_slider_args_',
        array(
            'type'     => 'select',
            'choices'  => $cat_posts,
            'label'    => __('Select Category to display Details', 'resort-vacation'),
            'section'  => 'resort_vacation_section_featured_about',
        )
    );

    for ($i=1; $i <=10 ; $i++) { 
    $wp_customize->add_setting('resort_vacation_room_square_fit_area'.$i, 
        array(
        'default'           => '',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',    
        'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('resort_vacation_room_square_fit_area'.$i, 
        array(
        'label'       => __('Square Feet Area ', 'resort-vacation').$i,
        'section'     => 'resort_vacation_section_featured_about',   
        'settings'    => 'resort_vacation_room_square_fit_area'.$i,
        'type'        => 'text'
        )
    );

    // Section Text
    $wp_customize->add_setting('resort_vacation_room_person'.$i, 
        array(
        'default'           => '',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',    
        'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('resort_vacation_room_person'.$i, 
        array(
        'label'       => __('Count for Guest ', 'resort-vacation').$i,
        'section'     => 'resort_vacation_section_featured_about',   
        'settings'    => 'resort_vacation_room_person'.$i,
        'type'        => 'text'
        )
    );

    // Section Text
    $wp_customize->add_setting('resort_vacation_room_rent'.$i, 
        array(
        'default'           => '',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',    
        'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('resort_vacation_room_rent'.$i, 
        array(
        'label'       => __('Room Rent ', 'resort-vacation').$i,
        'section'     => 'resort_vacation_section_featured_about',   
        'settings'    => 'resort_vacation_room_rent'.$i,
        'type'        => 'text'
        )
    );
    }

   
    
    /** Home Page Settings Ends */
    
    /** Footer Section */
    $wp_customize->add_section(
        'resort_vacation_footer_section',
        array(
            'title' => __( 'Footer Settings', 'resort-vacation' ),
            'priority' => 70,
        )
    );

    /** Copyright Text */
    $wp_customize->add_setting(
        'resort_vacation_footer_copyright_text',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'resort_vacation_footer_copyright_text',
        array(
            'label' => __( 'Copyright Info', 'resort-vacation' ),
            'section' => 'resort_vacation_footer_section',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting('footer_background_image',
        array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        )
    );


    $wp_customize->add_control(
         new WP_Customize_Cropped_Image_Control($wp_customize, 'footer_background_image',
            array(
                'label' => esc_html__('Footer Background Image', 'resort-vacation'),
                'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'resort-vacation'), 1024, 800),
                'section' => 'resort_vacation_footer_section',
                'width' => 1024,
                'height' => 800,
                'flex_width' => true,
                'flex_height' => true,
                'priority' => 100,
            )
        )
    );

    /* Footer Background Color*/
    $wp_customize->add_setting(
        'footer_background_color',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'footer_background_color',
            array(
                'label' => __('Footer Widget Area Background Color', 'resort-vacation'),
                'section' => 'resort_vacation_footer_section',
                'type' => 'color',
            )
        )
    );

    // 404 PAGE SETTINGS
    $wp_customize->add_section(
        'resort_vacation_404_section',
        array(
            'title' => __( '404 Page Settings', 'resort-vacation' ),
            'priority' => 70,
        )
    );
   
    $wp_customize->add_setting('404_page_image', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw', // Sanitize as URL
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, '404_page_image', array(
        'label' => __('404 Page Image', 'resort-vacation'),
        'section' => 'resort_vacation_404_section',
        'settings' => '404_page_image',
    )));

    $wp_customize->add_setting('404_pagefirst_header', array(
        'default' => __('404', 'resort-vacation'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field', // Sanitize as text field
    ));

    $wp_customize->add_control('404_pagefirst_header', array(
        'type' => 'text',
        'label' => __('Heading', 'resort-vacation'),
        'section' => 'resort_vacation_404_section',
    ));

    // Setting for 404 page header
    $wp_customize->add_setting('404_page_header', array(
        'default' => __('Sorry, that page can\'t be found!', 'resort-vacation'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field', // Sanitize as text field
    ));

    $wp_customize->add_control('404_page_header', array(
        'type' => 'text',
        'label' => __('Heading', 'resort-vacation'),
        'section' => 'resort_vacation_404_section',
    ));
    /** Footer control END */

    function resort_vacation_sanitize_choices( $input, $setting ) {
        global $wp_customize; 
        $control = $wp_customize->get_control( $setting->id ); 
        if ( array_key_exists( $input, $control->choices ) ) {
            return $input;
        } else {
            return $setting->default;
        }
    }

    function resort_vacation_sanitize_choicess($input) {
    $valid = array(
        'Arial'          => 'Arial, sans-serif',
        'Verdana'        => 'Verdana, sans-serif',
        'Helvetica'      => 'Helvetica, sans-serif',
        'Times New Roman'=> '"Times New Roman", serif',
        'Georgia'        => 'Georgia, serif',
        'Courier New'    => '"Courier New", monospace',
        'Trebuchet MS'   => '"Trebuchet MS", sans-serif',
        'Tahoma'         => 'Tahoma, sans-serif',
        'Palatino'       => '"Palatino Linotype", serif',
        'Garamond'       => 'Garamond, serif',
        'Impact'         => 'Impact, sans-serif',
        'Comic Sans MS'  => '"Comic Sans MS", cursive, sans-serif',
        'Lucida Sans'    => '"Lucida Sans Unicode", sans-serif',
        'Arial Black'    => '"Arial Black", sans-serif',
        'Gill Sans'      => '"Gill Sans", sans-serif',
        'Segoe UI'       => '"Segoe UI", sans-serif',
        'Open Sans'      => '"Open Sans", sans-serif',
        'Roboto'         => 'Roboto, sans-serif',
        'Lato'           => 'Lato, sans-serif',
        'Montserrat'     => 'Montserrat, sans-serif',
    );

    return (array_key_exists($input, $valid)) ? $input : '';
}

    if ( ! function_exists( 'resort_vacation_sanitize_number_range' ) ) :

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

    endif;

    if ( ! function_exists( 'resort_vacation_dropdown_pages' ) ) :
        function resort_vacation_dropdown_pages( $page_id, $setting ) {
            // Ensure $input is an absolute integer.
            $page_id = absint( $page_id );
          
            // If $page_id is an ID of a published page, return it; otherwise, return the default.
            return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
        }
    endif;
}
add_action( 'customize_register', 'resort_vacation_customize_register' );
endif;

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function resort_vacation_customize_preview_js() {
    // Use minified libraries if SCRIPT_DEBUG is false
    $resort_vacation_build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $resort_vacation_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'resort_vacation_customizer', get_template_directory_uri() . '/js' . $resort_vacation_build . '/customizer' . $resort_vacation_suffix . '.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'resort_vacation_customize_preview_js' );