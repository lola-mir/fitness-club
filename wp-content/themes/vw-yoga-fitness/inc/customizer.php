<?php
/**
 * VW Yoga Fitness Theme Customizer
 *
 * @package VW Yoga Fitness
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function vw_yoga_fitness_custom_controls() {

    load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'vw_yoga_fitness_custom_controls' );

function vw_yoga_fitness_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.logo .site-title a',
	 	'render_callback' => 'vw_yoga_fitness_customize_partial_blogname',
	));

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => 'p.site-description',
		'render_callback' => 'vw_yoga_fitness_customize_partial_blogdescription',
	));

	//add home page setting pannel
	$VWYogaFitnessParentPanel = new VW_Yoga_Fitness_WP_Customize_Panel( $wp_customize, 'vw_yoga_fitness_panel_id', array(
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => esc_html__( 'VW Settings', 'vw-yoga-fitness' ),
		'priority' => 10,
	));

	$wp_customize->add_panel( $VWYogaFitnessParentPanel );

	$HomePageParentPanel = new VW_Yoga_Fitness_WP_Customize_Panel( $wp_customize, 'vw_yoga_fitness_homepage_panel', array(
		'title' => __( 'Homepage Settings', 'vw-yoga-fitness' ),
		'panel' => 'vw_yoga_fitness_panel_id',
	));

	$wp_customize->add_panel( $HomePageParentPanel );

	//Topbar
	$wp_customize->add_section( 'vw_yoga_fitness_topbar', array(
    	'title'      => __( 'Topbar Settings', 'vw-yoga-fitness' ),
		'priority'   => 30,
		'panel' => 'vw_yoga_fitness_homepage_panel'
	));

   	// Header Background color
	$wp_customize->add_setting('vw_yoga_fitness_header_background_color', array(
		'default'           => '#242323',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_header_background_color', array(
		'label'    => __('Header Background Color', 'vw-yoga-fitness'),
		'section'  => 'header_image',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_header_img_position',array(
	  'default' => 'center top',
	  'transport' => 'refresh',
	  'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_header_img_position',array(
		'type' => 'select',
		'label' => __('Header Image Position','vw-yoga-fitness'),
		'section' => 'header_image',
		'choices' 	=> array(
			'left top' 		=> esc_html__( 'Top Left', 'vw-yoga-fitness' ),
			'center top'   => esc_html__( 'Top', 'vw-yoga-fitness' ),
			'right top'   => esc_html__( 'Top Right', 'vw-yoga-fitness' ),
			'left center'   => esc_html__( 'Left', 'vw-yoga-fitness' ),
			'center center'   => esc_html__( 'Center', 'vw-yoga-fitness' ),
			'right center'   => esc_html__( 'Right', 'vw-yoga-fitness' ),
			'left bottom'   => esc_html__( 'Bottom Left', 'vw-yoga-fitness' ),
			'center bottom'   => esc_html__( 'Bottom', 'vw-yoga-fitness' ),
			'right bottom'   => esc_html__( 'Bottom Right', 'vw-yoga-fitness' ),
		),
	));

	//Sticky Header
	$wp_customize->add_setting( 'vw_yoga_fitness_sticky_header',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_sticky_header',array(
        'label' => esc_html__( 'Sticky Header','vw-yoga-fitness' ),
        'section' => 'vw_yoga_fitness_topbar'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_sticky_header_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_sticky_header_padding',array(
		'label'	=> __('Sticky Header Padding','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_search_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_search_hide_show',array(
		'label' => esc_html__( 'Show / Hide Search','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_topbar'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_search_icon',array(
		'default'	=> 'fas fa-search',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_search_icon',array(
		'label'	=> __('Add Search Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_topbar',
		'setting'	=> 'vw_yoga_fitness_search_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('vw_yoga_fitness_search_close_icon',array(
		'default'	=> 'fa fa-window-close',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_search_close_icon',array(
		'label'	=> __('Add Search Close Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_topbar',
		'setting'	=> 'vw_yoga_fitness_search_close_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting('vw_yoga_fitness_search_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_search_font_size',array(
		'label'	=> __('Search Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_topbar',
		'type'=> 'text'
	));

    $wp_customize->add_setting('vw_yoga_fitness_appointment_button_icon',array(
		'default'	=> 'far fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_appointment_button_icon',array(
		'label'	=> __('Add Button Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_topbar',
		'setting'	=> 'vw_yoga_fitness_appointment_button_icon',
		'type'		=> 'icon'
	)));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('vw_yoga_fitness_button_text', array(
		'selector' => '.top-btn a',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_button_text',
	));

	$wp_customize->add_setting('vw_yoga_fitness_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_text',array(
		'label'	=> __('Add Button Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Book Now', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_button_url',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_url',array(
		'label'	=> __('Add Button URL','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'www.example.com', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_topbar',
		'type'=> 'url'
	));

	//Menus Settings
	$wp_customize->add_section( 'vw_yoga_fitness_menu_section' , array(
    	'title' => __( 'Menus Settings', 'vw-yoga-fitness' ),
		'panel' => 'vw_yoga_fitness_homepage_panel'
	) );

	$wp_customize->add_setting('vw_yoga_fitness_navigation_menu_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_navigation_menu_font_size',array(
		'label'	=> __('Menus Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_menu_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_navigation_menu_font_weight',array(
        'default' => 600,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_navigation_menu_font_weight',array(
        'type' => 'select',
        'label' => __('Menus Font Weight','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_menu_section',
        'choices' => array(
        	'100' => __('100','vw-yoga-fitness'),
            '200' => __('200','vw-yoga-fitness'),
            '300' => __('300','vw-yoga-fitness'),
            '400' => __('400','vw-yoga-fitness'),
            '500' => __('500','vw-yoga-fitness'),
            '600' => __('600','vw-yoga-fitness'),
            '700' => __('700','vw-yoga-fitness'),
            '800' => __('800','vw-yoga-fitness'),
            '900' => __('900','vw-yoga-fitness'),
        ),
	) );

	// text trasform
	$wp_customize->add_setting('vw_yoga_fitness_menu_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_menu_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Menus Text Transform','vw-yoga-fitness'),
		'choices' => array(
            'Uppercase' => __('Uppercase','vw-yoga-fitness'),
            'Capitalize' => __('Capitalize','vw-yoga-fitness'),
            'Lowercase' => __('Lowercase','vw-yoga-fitness'),
        ),
		'section'=> 'vw_yoga_fitness_menu_section',
	));

	$wp_customize->add_setting('vw_yoga_fitness_menus_item_style',array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_menus_item_style',array(
        'type' => 'select',
        'section' => 'vw_yoga_fitness_menu_section',
		'label' => __('Menu Item Hover Style','vw-yoga-fitness'),
		'choices' => array(
            'None' => __('None','vw-yoga-fitness'),
            'Zoom In' => __('Zoom In','vw-yoga-fitness'),
        ),
	) );

	$wp_customize->add_setting('vw_yoga_fitness_header_menus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_header_menus_color', array(
		'label'    => __('Menus Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_menu_section',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_header_menus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_header_menus_hover_color', array(
		'label'    => __('Menus Hover Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_menu_section',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_header_submenus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_header_submenus_color', array(
		'label'    => __('Sub Menus Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_menu_section',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_header_submenus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_header_submenus_hover_color', array(
		'label'    => __('Sub Menus Hover Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_menu_section',
	)));

	//Slider
	$wp_customize->add_section( 'vw_yoga_fitness_slidersettings' , array(
    	'title'      => __( 'Slider Section', 'vw-yoga-fitness' ),
    	'description' => __('Free theme has 3 slides options, For unlimited slides and more options </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/themes/yoga-wordpress-theme/">GO PRO</a>','vw-yoga-fitness'),
		'priority'   => null,
		'panel' => 'vw_yoga_fitness_homepage_panel'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_slider_hide_show',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_slider_hide_show',array(
		'label' => esc_html__( 'Show / Hide Slider','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_slidersettings'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_slider_type',array(
        'default' => 'Default slider',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	) );
	$wp_customize->add_control('vw_yoga_fitness_slider_type', array(
        'type' => 'select',
        'label' => __('Slider Type','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_slidersettings',
        'choices' => array(
            'Default slider' => __('Default slider','vw-yoga-fitness'),
            'Advance slider' => __('Advance slider','vw-yoga-fitness'),
        ),
	));

	$wp_customize->add_setting('vw_yoga_fitness_advance_slider_shortcode',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_advance_slider_shortcode',array(
		'label'	=> __('Add Slider Shortcode','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_slidersettings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_advance_slider',

	));

    //Selective Refresh
    $wp_customize->selective_refresh->add_partial('vw_yoga_fitness_slider_hide_show',array(
		'selector'        => '#slider .inner_carousel h1',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_slider_hide_show',
	));

	for ( $count = 1; $count <= 3; $count++ ) {
		$wp_customize->add_setting( 'vw_yoga_fitness_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'vw_yoga_fitness_sanitize_dropdown_pages'
		));
		$wp_customize->add_control( 'vw_yoga_fitness_slider_page' . $count, array(
			'label'    => __( 'Select Slider Page', 'vw-yoga-fitness' ),
			'description' => __('Slider image size (1500 x 590)','vw-yoga-fitness'),
			'section'  => 'vw_yoga_fitness_slidersettings',
			'type'     => 'dropdown-pages',
			'active_callback' => 'vw_yoga_fitness_default_slider'
		));
	}

	$wp_customize->add_setting('vw_yoga_fitness_slider_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_slider_button_text',array(
		'label'	=> __('Add Slider Button Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Read More', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_slidersettings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	));

	$wp_customize->add_setting('vw_yoga_fitness_slider_btn_icon',array(
		'default'	=> 'fa fa-angle-right',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_slider_btn_icon',array(
		'label'	=> __('Add Slider Button Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_slidersettings',
		'setting'	=> 'vw_yoga_fitness_slider_btn_icon',
		'type'		=> 'icon',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	)));

	//content layout
	$wp_customize->add_setting('vw_yoga_fitness_slider_content_option',array(
        'default' => 'Left',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Image_Radio_Control($wp_customize, 'vw_yoga_fitness_slider_content_option', array(
        'type' => 'select',
        'label' => __('Slider Content Layouts','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_slidersettings',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/slider-content1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/slider-content2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/slider-content3.png',
    ),'active_callback' => 'vw_yoga_fitness_default_slider'
    )));

    //Slider content padding
    $wp_customize->add_setting('vw_yoga_fitness_slider_content_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_slider_content_padding_top_bottom',array(
		'label'	=> __('Slider Content Padding Top Bottom','vw-yoga-fitness'),
		'description'	=> __('Enter a value in %. Example:20%','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '50%', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_slidersettings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	));

	$wp_customize->add_setting('vw_yoga_fitness_slider_content_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_slider_content_padding_left_right',array(
		'label'	=> __('Slider Content Padding Left Right','vw-yoga-fitness'),
		'description'	=> __('Enter a value in %. Example:20%','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '50%', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_slidersettings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	));

    //Slider excerpt
	$wp_customize->add_setting( 'vw_yoga_fitness_slider_excerpt_number', array(
		'default'              => 30,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	));
	$wp_customize->add_control( 'vw_yoga_fitness_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_slidersettings',
		'type'        => 'range',
		'settings'    => 'vw_yoga_fitness_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),'active_callback' => 'vw_yoga_fitness_default_slider'
	));

	//Slider height
	$wp_customize->add_setting('vw_yoga_fitness_slider_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_slider_height',array(
		'label'	=> __('Slider Height','vw-yoga-fitness'),
		'description'	=> __('Specify the slider height (px).','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '500px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_slidersettings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_slider_speed', array(
		'default'  => 4000,
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_float'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_slider_speed', array(
		'label' => esc_html__('Slider Transition Speed','vw-yoga-fitness'),
		'section' => 'vw_yoga_fitness_slidersettings',
		'type'  => 'number',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	) );

	//Opacity
	$wp_customize->add_setting('vw_yoga_fitness_slider_opacity_color',array(
      'default'              => 0.5,
      'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control( 'vw_yoga_fitness_slider_opacity_color', array(
		'label'       => esc_html__( 'Slider Image Opacity','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_slidersettings',
		'type'        => 'select',
		'settings'    => 'vw_yoga_fitness_slider_opacity_color',
		'choices' => array(
			'0' =>  esc_attr('0','vw-yoga-fitness'),
			'0.1' =>  esc_attr('0.1','vw-yoga-fitness'),
			'0.2' =>  esc_attr('0.2','vw-yoga-fitness'),
			'0.3' =>  esc_attr('0.3','vw-yoga-fitness'),
			'0.4' =>  esc_attr('0.4','vw-yoga-fitness'),
			'0.5' =>  esc_attr('0.5','vw-yoga-fitness'),
			'0.6' =>  esc_attr('0.6','vw-yoga-fitness'),
			'0.7' =>  esc_attr('0.7','vw-yoga-fitness'),
			'0.8' =>  esc_attr('0.8','vw-yoga-fitness'),
			'0.9' =>  esc_attr('0.9','vw-yoga-fitness')
	),'active_callback' => 'vw_yoga_fitness_default_slider'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_slider_image_overlay',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new vw_yoga_fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_slider_image_overlay',array(
      	'label' => esc_html__( 'Slider Image Overlay','vw-yoga-fitness' ),
      	'section' => 'vw_yoga_fitness_slidersettings',
      	'active_callback' => 'vw_yoga_fitness_default_slider'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_slider_image_overlay_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_slider_image_overlay_color', array(
		'label'    => __('Slider Image Overlay Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_slidersettings',
		'active_callback' => 'vw_yoga_fitness_default_slider'
	)));

	//we offer Section
	$wp_customize->add_section('vw_yoga_fitness_we_offer', array(
		'title'       => __('We Offer Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_we_offer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_we_offer_text',array(
		'description' => __('<p>1. More options for we offer section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for we offer section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_we_offer',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_we_offer_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_we_offer_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_we_offer',
		'type'=> 'hidden'
	));

	//records Section
	$wp_customize->add_section('vw_yoga_fitness_records', array(
		'title'       => __('Records Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_records_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_records_text',array(
		'description' => __('<p>1. More options for records section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for records section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_records',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_records_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_records_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_records',
		'type'=> 'hidden'
	));

	//Services
	$wp_customize->add_section( 'vw_yoga_fitness_service_section' , array(
    	'title'      => __( 'Our Classes Section', 'vw-yoga-fitness' ),
    	'description' => __('For more options of the Our Classes Section </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/themes/yoga-wordpress-theme/">GO PRO</a>','vw-yoga-fitness'),
		'priority'   => null,
		'panel' => 'vw_yoga_fitness_homepage_panel'
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'vw_yoga_fitness_section_title', array(
		'selector' => '#serv-section h2',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_section_title',
	));

	$wp_customize->add_setting('vw_yoga_fitness_section_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_section_title',array(
		'label'	=> __('Section Title','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Our Classes', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_service_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_section_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_section_text',array(
		'label'	=> __('Section Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Lorem ipsum is a dummy text.', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_service_section',
		'type'=> 'text'
	));

	$categories = get_categories();
	$cat_post = array();
	$cat_post[]= 'select';
	$i = 0;
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_post[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('vw_yoga_fitness_services',array(
		'default'	=> 'select',
		'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices',
	));
	$wp_customize->add_control('vw_yoga_fitness_services',array(
		'type'    => 'select',
		'choices' => $cat_post,
		'label' => __('Select Category to display services','vw-yoga-fitness'),
		'description' => __('Image Size (340 x 255)','vw-yoga-fitness'),
		'section' => 'vw_yoga_fitness_service_section',
	));

	//Classes excerpt
	$wp_customize->add_setting( 'vw_yoga_fitness_classes_excerpt_number', array(
		'default'              => 30,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	));
	$wp_customize->add_control( 'vw_yoga_fitness_classes_excerpt_number', array(
		'label'       => esc_html__( 'Classes Excerpt length','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_service_section',
		'type'        => 'range',
		'settings'    => 'vw_yoga_fitness_classes_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	));

	$wp_customize->add_setting('vw_yoga_fitness_classes_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_classes_button_text',array(
		'label'	=> __('Add Classes Button Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Read More', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_service_section',
		'type'=> 'text'
	));

	//experience Section
	$wp_customize->add_section('vw_yoga_fitness_experience', array(
		'title'       => __('Experience Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_experience_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_experience_text',array(
		'description' => __('<p>1. More options for experience section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for experience section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_experience',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_experience_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_experience_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_experience',
		'type'=> 'hidden'
	));

	//gallery Section
	$wp_customize->add_section('vw_yoga_fitness_gallery', array(
		'title'       => __('Gallery Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_gallery_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_gallery_text',array(
		'description' => __('<p>1. More options for gallery section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for gallery section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_gallery',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_gallery_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_gallery_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_gallery',
		'type'=> 'hidden'
	));

	//register now Section
	$wp_customize->add_section('vw_yoga_fitness_register_now', array(
		'title'       => __('Register Now Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_register_now_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_register_now_text',array(
		'description' => __('<p>1. More options for register now section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for register now section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_register_now',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_register_now_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_register_now_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_register_now',
		'type'=> 'hidden'
	));

	//pricing plan Section
	$wp_customize->add_section('vw_yoga_fitness_pricing_plan', array(
		'title'       => __('Pricing Plan Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_pricing_plan_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_pricing_plan_text',array(
		'description' => __('<p>1. More options for pricing plan section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for pricing plan section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_pricing_plan',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_pricing_plan_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_pricing_plan_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_pricing_plan',
		'type'=> 'hidden'
	));

	//choose us Section
	$wp_customize->add_section('vw_yoga_fitness_choose_us', array(
		'title'       => __('Choose Us Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_choose_us_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_choose_us_text',array(
		'description' => __('<p>1. More options for choose us section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for choose us section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_choose_us',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_choose_us_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_choose_us_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_choose_us',
		'type'=> 'hidden'
	));


	//products Section
	$wp_customize->add_section('vw_yoga_fitness_products', array(
		'title'       => __('Products Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_products_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_text',array(
		'description' => __('<p>1. More options for products section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for products section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_products',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_products_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_products',
		'type'=> 'hidden'
	));

	//testimonials Section
	$wp_customize->add_section('vw_yoga_fitness_testimonials', array(
		'title'       => __('Testimonials Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_testimonials_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_testimonials_text',array(
		'description' => __('<p>1. More options for testimonials section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for testimonials section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_testimonials',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_testimonials_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_testimonials_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_testimonials',
		'type'=> 'hidden'
	));

	//upcoming events Section
	$wp_customize->add_section('vw_yoga_fitness_upcoming_events', array(
		'title'       => __('Upcoming Events Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_upcoming_events_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_upcoming_events_text',array(
		'description' => __('<p>1. More options for upcoming events section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for upcoming events section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_upcoming_events',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_upcoming_events_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_upcoming_events_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_upcoming_events',
		'type'=> 'hidden'
	));

	//blog Section
	$wp_customize->add_section('vw_yoga_fitness_blog', array(
		'title'       => __('Blog Section', 'vw-yoga-fitness'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-yoga-fitness'),
		'priority'    => null,
		'panel'       => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting('vw_yoga_fitness_blog_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_blog_text',array(
		'description' => __('<p>1. More options for blog section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for blog section.</p>','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_blog',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('vw_yoga_fitness_blog_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_blog_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_yoga_fitness_guide') ." '>More Info</a>",
		'section'=> 'vw_yoga_fitness_blog',
		'type'=> 'hidden'
	));

	//Footer Text
	$wp_customize->add_section('vw_yoga_fitness_footer',array(
		'title'	=> __('Footer','vw-yoga-fitness'),
		'description' => __('For more options of the footer section </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/themes/yoga-wordpress-theme/">GO PRO</a>','vw-yoga-fitness'),
		'panel' => 'vw_yoga_fitness_homepage_panel',
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_footer_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new vw_yoga_fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_footer_hide_show',array(
      'label' => esc_html__( 'Show / Hide Footer','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_footer'
    )));

	$wp_customize->add_setting('vw_yoga_fitness_footer_background_color', array(
		'default'           => '#242323',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_footer_background_color', array(
		'label'    => __('Footer Background Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_footer',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_footer_background_image',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'vw_yoga_fitness_footer_background_image',array(
        'label' => __('Footer Background Image','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_footer'
	)));

	$wp_customize->add_setting('vw_yoga_fitness_footer_img_position',array(
	  'default' => 'center center',
	  'transport' => 'refresh',
	  'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_footer_img_position',array(
		'type' => 'select',
		'label' => __('Footer Image Position','vw-yoga-fitness'),
		'section' => 'vw_yoga_fitness_footer',
		'choices' 	=> array(
			'left top' 		=> esc_html__( 'Top Left', 'vw-yoga-fitness' ),
			'center top'   => esc_html__( 'Top', 'vw-yoga-fitness' ),
			'right top'   => esc_html__( 'Top Right', 'vw-yoga-fitness' ),
			'left center'   => esc_html__( 'Left', 'vw-yoga-fitness' ),
			'center center'   => esc_html__( 'Center', 'vw-yoga-fitness' ),
			'right center'   => esc_html__( 'Right', 'vw-yoga-fitness' ),
			'left bottom'   => esc_html__( 'Bottom Left', 'vw-yoga-fitness' ),
			'center bottom'   => esc_html__( 'Bottom', 'vw-yoga-fitness' ),
			'right bottom'   => esc_html__( 'Bottom Right', 'vw-yoga-fitness' ),
		),
	));

	// Footer
	$wp_customize->add_setting('vw_yoga_fitness_img_footer',array(
		'default'=> 'scroll',
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_img_footer',array(
		'type' => 'select',
		'label'	=> __('Footer Background Attatchment','vw-yoga-fitness'),
		'choices' => array(
            'fixed' => __('fixed','vw-yoga-fitness'),
            'scroll' => __('scroll','vw-yoga-fitness'),
        ),
		'section'=> 'vw_yoga_fitness_footer',
	));

	// footer padding
	$wp_customize->add_setting('vw_yoga_fitness_footer_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_footer_padding',array(
		'label'	=> __('Footer Top Bottom Padding','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
      'placeholder' => __( '10px', 'vw-yoga-fitness' ),
    ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_footer_widgets_heading',array(
        'default' => 'Left',
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_footer_widgets_heading',array(
        'type' => 'select',
        'label' => __('Footer Widget Heading','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_footer',
        'choices' => array(
        	'Left' => __('Left','vw-yoga-fitness'),
            'Center' => __('Center','vw-yoga-fitness'),
            'Right' => __('Right','vw-yoga-fitness')
        ),
	) );

	$wp_customize->add_setting('vw_yoga_fitness_footer_widgets_content',array(
        'default' => 'Left',
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_footer_widgets_content',array(
        'type' => 'select',
        'label' => __('Footer Widget Content','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_footer',
        'choices' => array(
        	'Left' => __('Left','vw-yoga-fitness'),
            'Center' => __('Center','vw-yoga-fitness'),
            'Right' => __('Right','vw-yoga-fitness')
        ),
	) );

    // footer social icon
  	$wp_customize->add_setting( 'vw_yoga_fitness_footer_icon',array(
		'default' => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
  	$wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_footer_icon',array(
		'label' => esc_html__( 'Footer Social Icon','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_footer'
    )));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('vw_yoga_fitness_footer_text', array(
		'selector' => '.footer-2 .copyright p',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_footer_text',
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_copyright_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new vw_yoga_fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_copyright_hide_show',array(
      'label' => esc_html__( 'Show / Hide Copyright','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_footer'
    )));

	$wp_customize->add_setting('vw_yoga_fitness_copyright_background_color', array(
		'default'           => '#a887c9',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_copyright_background_color', array(
		'label'    => __('Copyright Background Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_footer',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_footer_text',array(
		'label'	=> __('Copyright Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Copyright 2018, .....', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_copyright_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_copyright_font_size',array(
		'label'	=> __('Copyright Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_copyright_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_copyright_padding_top_bottom',array(
		'label'	=> __('Copyright Padding Top Bottom','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_copyright_alignment',array(
        'default' => 'center',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Image_Radio_Control($wp_customize, 'vw_yoga_fitness_copyright_alignment', array(
        'type' => 'select',
        'label' => __('Copyright Alignment','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_footer',
        'settings' => 'vw_yoga_fitness_copyright_alignment',
        'choices' => array(
            'left' => esc_url(get_template_directory_uri()).'/assets/images/copyright1.png',
            'center' => esc_url(get_template_directory_uri()).'/assets/images/copyright2.png',
            'right' => esc_url(get_template_directory_uri()).'/assets/images/copyright3.png'
    ))));

	$wp_customize->add_setting( 'vw_yoga_fitness_hide_show_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_hide_show_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','vw-yoga-fitness' ),
      	'section' => 'vw_yoga_fitness_footer'
    )));

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial('vw_yoga_fitness_scroll_to_top_icon', array(
		'selector' => '.scrollup i',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_scroll_to_top_icon',
	));

    $wp_customize->add_setting('vw_yoga_fitness_scroll_to_top_icon',array(
		'default'	=> 'fas fa-angle-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_scroll_to_top_icon',array(
		'label'	=> __('Add Scroll to Top Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_footer',
		'setting'	=> 'vw_yoga_fitness_scroll_to_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('vw_yoga_fitness_scroll_to_top_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_scroll_to_top_font_size',array(
		'label'	=> __('Icon Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_scroll_to_top_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_scroll_to_top_padding',array(
		'label'	=> __('Icon Top Bottom Padding','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_scroll_to_top_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_scroll_to_top_width',array(
		'label'	=> __('Icon Width','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_scroll_to_top_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_scroll_to_top_height',array(
		'label'	=> __('Icon Height','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_scroll_to_top_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_scroll_to_top_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_footer',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('vw_yoga_fitness_scroll_top_alignment',array(
        'default' => 'Right',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Image_Radio_Control($wp_customize, 'vw_yoga_fitness_scroll_top_alignment', array(
        'type' => 'select',
        'label' => __('Scroll To Top','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_footer',
        'settings' => 'vw_yoga_fitness_scroll_top_alignment',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/layout2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/layout3.png'
    ))));

    //Blog Post

	$BlogPostParentPanel = new VW_Yoga_Fitness_WP_Customize_Panel( $wp_customize, 'blog_post_parent_panel', array(
		'title' => __( 'Blog Post Settings', 'vw-yoga-fitness' ),
		'panel' => 'vw_yoga_fitness_panel_id',
	));

	$wp_customize->add_panel( $BlogPostParentPanel );

	// Add example section and controls to the middle (second) panel
	$wp_customize->add_section( 'vw_yoga_fitness_post_settings', array(
		'title' => __( 'Post Settings', 'vw-yoga-fitness' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Blog layout
    $wp_customize->add_setting('vw_yoga_fitness_blog_layout_option',array(
        'default' => 'Default',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
    ));
    $wp_customize->add_control(new VW_Yoga_Fitness_Image_Radio_Control($wp_customize, 'vw_yoga_fitness_blog_layout_option', array(
        'type' => 'select',
        'label' => __('Blog Layouts','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_post_settings',
        'choices' => array(
            'Default' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout2.png',
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout3.png',
    ))));

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('vw_yoga_fitness_theme_options',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_theme_options', array(
        'type' => 'select',
        'label' => __('Post Sidebar Layout','vw-yoga-fitness'),
        'description' => __('Here you can change the sidebar layout for posts. ','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_post_settings',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-yoga-fitness'),
            'Right Sidebar' => __('Right Sidebar','vw-yoga-fitness'),
            'One Column' => __('One Column','vw-yoga-fitness'),
            'Three Columns' => __('Three Columns','vw-yoga-fitness'),
            'Four Columns' => __('Four Columns','vw-yoga-fitness'),
            'Grid Layout' => __('Grid Layout','vw-yoga-fitness')
        ),
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('vw_yoga_fitness_toggle_postdate', array(
		'selector' => '.post-main-box h2 a',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_toggle_postdate',
	));

	$wp_customize->add_setting('vw_yoga_fitness_toggle_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_toggle_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_post_settings',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'vw_yoga_fitness_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_toggle_postdate',array(
        'label' => esc_html__( 'Show / Hide Post Date','vw-yoga-fitness' ),
        'section' => 'vw_yoga_fitness_post_settings'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_toggle_author_icon',array(
		'default'	=> 'far fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_toggle_author_icon',array(
		'label'	=> __('Add Author Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_post_settings',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'vw_yoga_fitness_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_toggle_author',array(
		'label' => esc_html__( 'Show / Hide Author','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_post_settings'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_toggle_comments_icon',array(
		'default'	=> 'fas fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_toggle_comments_icon',array(
		'label'	=> __('Add Comments Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_post_settings',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'vw_yoga_fitness_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_toggle_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_post_settings'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_toggle_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_toggle_time_icon',array(
		'label'	=> __('Add Time Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_post_settings',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'vw_yoga_fitness_toggle_time',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_toggle_time',array(
		'label' => esc_html__( 'Show / Hide Time','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_post_settings'
    )));

    $wp_customize->add_setting( 'vw_yoga_fitness_featured_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_featured_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_post_settings'
    )));

    $wp_customize->add_setting( 'vw_yoga_fitness_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_featured_image_border_radius', array(
		'label'       => esc_html__( 'Featured Image Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'vw_yoga_fitness_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Featured Image Box Shadow','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Featured Image
	$wp_customize->add_setting('vw_yoga_fitness_blog_post_featured_image_dimension',array(
       'default' => 'default',
       'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_choices'
	));
  $wp_customize->add_control('vw_yoga_fitness_blog_post_featured_image_dimension',array(
     'type' => 'select',
     'label'	=> __('Blog Post Featured Image Dimension','vw-yoga-fitness'),
     'section'	=> 'vw_yoga_fitness_post_settings',
     'choices' => array(
          'default' => __('Default','vw-yoga-fitness'),
          'custom' => __('Custom Image Size','vw-yoga-fitness'),
      ),
  ));

	$wp_customize->add_setting('vw_yoga_fitness_blog_post_featured_image_custom_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
		));
	$wp_customize->add_control('vw_yoga_fitness_blog_post_featured_image_custom_width',array(
		'label'	=> __('Featured Image Custom Width','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
		'placeholder' => __( '10px', 'vw-yoga-fitness' ),),
		'section'=> 'vw_yoga_fitness_post_settings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_blog_post_featured_image_dimension'
		));

	$wp_customize->add_setting('vw_yoga_fitness_blog_post_featured_image_custom_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_blog_post_featured_image_custom_height',array(
		'label'	=> __('Featured Image Custom Height','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
		'placeholder' => __( '10px', 'vw-yoga-fitness' ),),
		'section'=> 'vw_yoga_fitness_post_settings',
		'type'=> 'text',
		'active_callback' => 'vw_yoga_fitness_blog_post_featured_image_dimension'
	));

    $wp_customize->add_setting( 'vw_yoga_fitness_excerpt_number', array(
		'default'              => 30,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	));
	$wp_customize->add_control( 'vw_yoga_fitness_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_post_settings',
		'type'        => 'range',
		'settings'    => 'vw_yoga_fitness_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	));

	$wp_customize->add_setting('vw_yoga_fitness_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','vw-yoga-fitness'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_post_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('vw_yoga_fitness_blog_page_posts_settings',array(
        'default' => 'Into Blocks',
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_blog_page_posts_settings',array(
        'type' => 'select',
        'label' => __('Display Blog posts','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_post_settings',
        'choices' => array(
        	'Into Blocks' => __('Into Blocks','vw-yoga-fitness'),
            'Without Blocks' => __('Without Blocks','vw-yoga-fitness')
        ),
	) );

    $wp_customize->add_setting('vw_yoga_fitness_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_excerpt_settings',array(
        'type' => 'select',
        'label' => __('Post Content','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_post_settings',
        'choices' => array(
        	'Content' => __('Content','vw-yoga-fitness'),
            'Excerpt' => __('Excerpt','vw-yoga-fitness'),
            'No Content' => __('No Content','vw-yoga-fitness')
        ),
	));

	$wp_customize->add_setting('vw_yoga_fitness_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_post_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_blog_pagination_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_blog_pagination_hide_show',array(
      'label' => esc_html__( 'Show / Hide Blog Pagination','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_post_settings'
    )));

	$wp_customize->add_setting( 'vw_yoga_fitness_blog_pagination_type', array(
        'default'			=> 'blog-page-numbers',
        'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_choices'
    ));
    $wp_customize->add_control( 'vw_yoga_fitness_blog_pagination_type', array(
        'section' => 'vw_yoga_fitness_post_settings',
        'type' => 'select',
        'label' => __( 'Blog Pagination', 'vw-yoga-fitness' ),
        'choices'		=> array(
            'blog-page-numbers'  => __( 'Numeric', 'vw-yoga-fitness' ),
            'next-prev' => __( 'Older Posts/Newer Posts', 'vw-yoga-fitness' ),
    )));

    // Button Settings
	$wp_customize->add_section( 'vw_yoga_fitness_button_settings', array(
		'title' => __( 'Button Settings', 'vw-yoga-fitness' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('vw_yoga_fitness_blog_button_text', array(
		'selector' => '.post-main-box .content-bttn a',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_blog_button_text',
	));

	$wp_customize->add_setting('vw_yoga_fitness_blog_button_text',array(
		'default'=> esc_html__('Read More','vw-yoga-fitness'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_blog_button_text',array(
		'label'	=> __('Add Button Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Read More', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_blog_button_icon',array(
		'default'	=> 'fa fa-angle-right',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_blog_button_icon',array(
		'label'	=> __('Add Button Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_button_settings',
		'setting'	=> 'vw_yoga_fitness_blog_button_icon',
		'type'		=> 'icon'
	)));

	// font size button
	$wp_customize->add_setting('vw_yoga_fitness_button_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_font_size',array(
		'label'	=> __('Button Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'vw-yoga-fitness' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'vw_yoga_fitness_button_settings',
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_button_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	));
	$wp_customize->add_control( 'vw_yoga_fitness_button_border_radius', array(
		'label'       => esc_html__( 'Button Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_button_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	));

	$wp_customize->add_setting('vw_yoga_fitness_button_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_padding_top_bottom',array(
		'label'	=> __('Padding Top Bottom','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_button_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_padding_left_right',array(
		'label'	=> __('Padding Left Right','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_button_letter_spacing',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_letter_spacing',array(
		'label'	=> __('Button Letter Spacing','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'vw-yoga-fitness' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'vw_yoga_fitness_button_settings',
	));

	// text trasform
	$wp_customize->add_setting('vw_yoga_fitness_button_text_transform',array(
		'default'=> 'Uppercase',
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_button_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Button Text Transform','vw-yoga-fitness'),
		'choices' => array(
            'Uppercase' => __('Uppercase','vw-yoga-fitness'),
            'Capitalize' => __('Capitalize','vw-yoga-fitness'),
            'Lowercase' => __('Lowercase','vw-yoga-fitness'),
        ),
		'section'=> 'vw_yoga_fitness_button_settings',
	));

	// Related Post Settings
	$wp_customize->add_section( 'vw_yoga_fitness_related_posts_settings', array(
		'title' => __( 'Related Posts Settings', 'vw-yoga-fitness' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('vw_yoga_fitness_related_post_title', array(
		'selector' => '.related-post h3',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_related_post_title',
	));

    $wp_customize->add_setting( 'vw_yoga_fitness_related_post',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_related_post',array(
		'label' => esc_html__( 'Show / Hide Related Post','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_related_posts_settings'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_related_post_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_related_post_title',array(
		'label'	=> __('Add Related Post Title','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Related Post', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_related_posts_settings',
		'type'=> 'text'
	));

   	$wp_customize->add_setting('vw_yoga_fitness_related_posts_count',array(
		'default'=> '3',
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_float'
	));
	$wp_customize->add_control('vw_yoga_fitness_related_posts_count',array(
		'label'	=> __('Add Related Post Count','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '3', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_related_posts_settings',
		'type'=> 'number'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_related_posts_excerpt_number', array(
		'default'              => 20,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_related_posts_excerpt_number', array(
		'label'       => esc_html__( 'Related Posts Excerpt length','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_related_posts_settings',
		'type'        => 'range',
		'settings'    => 'vw_yoga_fitness_related_posts_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	// Single Posts Settings
	$wp_customize->add_section( 'vw_yoga_fitness_single_blog_settings', array(
		'title' => __( 'Single Post Settings', 'vw-yoga-fitness' ),
		'panel' => 'blog_post_parent_panel',
	));

  	$wp_customize->add_setting('vw_yoga_fitness_single_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_single_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_single_blog_settings',
		'setting'	=> 'vw_yoga_fitness_single_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'vw_yoga_fitness_single_postdate',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	) );
	$wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_postdate',array(
	    'label' => esc_html__( 'Show / Hide Date','vw-yoga-fitness' ),
	   'section' => 'vw_yoga_fitness_single_blog_settings'
	)));

	$wp_customize->add_setting('vw_yoga_fitness_single_author_icon',array(
		'default'	=> 'far fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_single_author_icon',array(
		'label'	=> __('Add Author Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_single_blog_settings',
		'setting'	=> 'vw_yoga_fitness_single_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'vw_yoga_fitness_single_author',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	) );
	$wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_author',array(
	    'label' => esc_html__( 'Show / Hide Author','vw-yoga-fitness' ),
	    'section' => 'vw_yoga_fitness_single_blog_settings'
	)));

   	$wp_customize->add_setting('vw_yoga_fitness_single_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_single_comments_icon',array(
		'label'	=> __('Add Comments Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_single_blog_settings',
		'setting'	=> 'vw_yoga_fitness_single_comments_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'vw_yoga_fitness_single_comments',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	) );
	$wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_comments',array(
	    'label' => esc_html__( 'Show / Hide Comments','vw-yoga-fitness' ),
	    'section' => 'vw_yoga_fitness_single_blog_settings'
	)));

  	$wp_customize->add_setting('vw_yoga_fitness_single_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_single_time_icon',array(
		'label'	=> __('Add Time Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_single_blog_settings',
		'setting'	=> 'vw_yoga_fitness_single_time_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'vw_yoga_fitness_single_time',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	) );
	$wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_time',array(
	    'label' => esc_html__( 'Show / Hide Time','vw-yoga-fitness' ),
	    'section' => 'vw_yoga_fitness_single_blog_settings'
	)));

	$wp_customize->add_setting( 'vw_yoga_fitness_single_post_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_post_breadcrumb',array(
		'label' => esc_html__( 'Show / Hide Post Breadcrumb','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_single_blog_settings'
    )));

    // Single Posts Category
  	$wp_customize->add_setting( 'vw_yoga_fitness_single_post_category',array(
		'default' => true,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
  	$wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_post_category',array(
		'label' => esc_html__( 'Show / Hide Post Category','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_single_blog_settings'
    )));

	$wp_customize->add_setting( 'vw_yoga_fitness_toggle_tags',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_toggle_tags', array(
		'label' => esc_html__( 'Show / Hide Tags','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_single_blog_settings'
    )));

	$wp_customize->add_setting( 'vw_yoga_fitness_single_blog_post_navigation_show_hide',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
	));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_blog_post_navigation_show_hide', array(
		'label' => esc_html__( 'Show / Hide Post Navigation','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_single_blog_settings'
    )));

	$wp_customize->add_setting('vw_yoga_fitness_single_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_single_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','vw-yoga-fitness'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_single_blog_settings',
		'type'=> 'text'
	));

	//navigation text
	$wp_customize->add_setting('vw_yoga_fitness_single_blog_prev_navigation_text',array(
		'default'=> 'PREVIOUS',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_single_blog_prev_navigation_text',array(
		'label'	=> __('Post Navigation Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'PREVIOUS', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_single_blog_next_navigation_text',array(
		'default'=> 'NEXT',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_single_blog_next_navigation_text',array(
		'label'	=> __('Post Navigation Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'NEXT', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_single_blog_comment_title',array(
		'default'=> 'Leave a Reply',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('vw_yoga_fitness_single_blog_comment_title',array(
		'label'	=> __('Add Comment Title','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Leave a Reply', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_single_blog_comment_button_text',array(
		'default'=> 'Post Comment',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('vw_yoga_fitness_single_blog_comment_button_text',array(
		'label'	=> __('Add Comment Button Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Post Comment', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_single_blog_comment_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_single_blog_comment_width',array(
		'label'	=> __('Comment Form Width','vw-yoga-fitness'),
		'description'	=> __('Enter a value in %. Example:50%','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '100%', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_single_blog_settings',
		'type'=> 'text'
	));

	// Grid layout setting
	$wp_customize->add_section( 'vw_yoga_fitness_grid_layout_settings', array(
		'title' => __( 'Grid Layout Settings', 'vw-yoga-fitness' ),
		'panel' => 'blog_post_parent_panel',
	));

  	$wp_customize->add_setting('vw_yoga_fitness_grid_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_grid_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_grid_layout_settings',
		'setting'	=> 'vw_yoga_fitness_grid_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'vw_yoga_fitness_grid_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_grid_postdate',array(
        'label' => esc_html__( 'Show / Hide Post Date','vw-yoga-fitness' ),
        'section' => 'vw_yoga_fitness_grid_layout_settings'
    )));

	$wp_customize->add_setting('vw_yoga_fitness_grid_author_icon',array(
		'default'	=> 'far fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_grid_author_icon',array(
		'label'	=> __('Add Author Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_grid_layout_settings',
		'setting'	=> 'vw_yoga_fitness_grid_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'vw_yoga_fitness_grid_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_grid_author',array(
		'label' => esc_html__( 'Show / Hide Author','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_grid_layout_settings'
    )));

   	$wp_customize->add_setting('vw_yoga_fitness_grid_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_grid_comments_icon',array(
		'label'	=> __('Add Comments Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_grid_layout_settings',
		'setting'	=> 'vw_yoga_fitness_grid_comments_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'vw_yoga_fitness_grid_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_grid_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_grid_layout_settings'
    )));

 	$wp_customize->add_setting('vw_yoga_fitness_grid_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_grid_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','vw-yoga-fitness'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','vw-yoga-fitness'),
		'section'=> 'vw_yoga_fitness_grid_layout_settings',
		'type'=> 'text'
	));  

  	$wp_customize->add_setting('vw_yoga_fitness_display_grid_posts_settings',array(
	    'default' => 'Into Blocks',
	    'transport' => 'refresh',
	    'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_display_grid_posts_settings',array(
	    'type' => 'select',
	    'label' => __('Display Grid Posts','vw-yoga-fitness'),
	    'section' => 'vw_yoga_fitness_grid_layout_settings',
	    'choices' => array(
	    	'Into Blocks' => __('Into Blocks','vw-yoga-fitness'),
	      'Without Blocks' => __('Without Blocks','vw-yoga-fitness')
      	),
	) );


   // other settings
	$OtherParentPanel = new VW_Yoga_Fitness_WP_Customize_Panel( $wp_customize, 'vw_yoga_fitness_other_panel_id', array(
		'title' => __( 'Others Settings', 'vw-yoga-fitness' ),
		'panel' => 'vw_yoga_fitness_panel_id',
	));

	$wp_customize->add_panel( $OtherParentPanel );

	$wp_customize->add_section( 'vw_yoga_fitness_left_right', array(
    	'title'      => esc_html__( 'General Settings', 'vw-yoga-fitness' ),
		'priority'   => 30,
		'panel' => 'vw_yoga_fitness_other_panel_id'
	));

	$wp_customize->add_setting('vw_yoga_fitness_width_option',array(
        'default' => 'Full Width',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Image_Radio_Control($wp_customize, 'vw_yoga_fitness_width_option', array(
        'type' => 'select',
        'label' => __('Width Layouts','vw-yoga-fitness'),
        'description' => __('Here you can change the width layout of Website.','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_left_right',
        'choices' => array(
            'Full Width' => esc_url(get_template_directory_uri()).'/assets/images/full-width.png',
            'Wide Width' => esc_url(get_template_directory_uri()).'/assets/images/wide-width.png',
            'Boxed' => esc_url(get_template_directory_uri()).'/assets/images/boxed-width.png',
    ))));

	$wp_customize->add_setting('vw_yoga_fitness_page_layout',array(
        'default' => 'One Column',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_page_layout',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','vw-yoga-fitness'),
        'description' => __('Here you can change the sidebar layout for pages. ','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-yoga-fitness'),
            'Right Sidebar' => __('Right Sidebar','vw-yoga-fitness'),
            'One Column' => __('One Column','vw-yoga-fitness')
        ),
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_single_page_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_single_page_breadcrumb',array(
		'label' => esc_html__( 'Show / Hide Page Breadcrumb','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_left_right'
    )));

	//Wow Animation
	$wp_customize->add_setting( 'vw_yoga_fitness_animation',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_animation',array(
        'label' => esc_html__( 'Show / Hide Animation ','vw-yoga-fitness' ),
        'description' => __('Here you can disable overall site animation effect','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_left_right'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_reset_all_settings',array(
      'sanitize_callback'	=> 'sanitize_text_field',
   	));
   	$wp_customize->add_control(new VW_Yoga_Fitness_Reset_Custom_Control($wp_customize, 'vw_yoga_fitness_reset_all_settings',array(
      'type' => 'reset_control',
      'label' => __('Reset All Settings', 'vw-yoga-fitness'),
      'description' => 'vw_yoga_fitness_reset_all_settings',
      'section' => 'vw_yoga_fitness_left_right'
   	)));

	//Pre-Loader
	$wp_customize->add_setting( 'vw_yoga_fitness_loader_enable',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_loader_enable',array(
        'label' => esc_html__( 'Show / Hide Pre-Loader','vw-yoga-fitness' ),
        'section' => 'vw_yoga_fitness_left_right'
    )));

	$wp_customize->add_setting('vw_yoga_fitness_preloader_bg_color', array(
		'default'           => '#a887c9',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_preloader_bg_color', array(
		'label'    => __('Pre-Loader Background Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_left_right',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_preloader_border_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_preloader_border_color', array(
		'label'    => __('Pre-Loader Border Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_left_right',
	)));

	$wp_customize->add_setting('vw_yoga_fitness_preloader_bg_img',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'vw_yoga_fitness_preloader_bg_img',array(
        'label' => __('Preloader Background Image','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_left_right'
	)));

    //404 Page Setting
	$wp_customize->add_section('vw_yoga_fitness_404_page',array(
		'title'	=> __('404 Page Settings','vw-yoga-fitness'),
		'panel' => 'vw_yoga_fitness_other_panel_id',
	));

	$wp_customize->add_setting('vw_yoga_fitness_404_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_404_page_title',array(
		'label'	=> __('Add Title','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '404 Not Found', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_404_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_404_page_content',array(
		'label'	=> __('Add Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_404_page_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_404_page_button_text',array(
		'label'	=> __('Add Button Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Return to the home page', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_404_page_btn_icon',array(
		'default'	=> 'fa fa-angle-right',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_404_page_btn_icon',array(
		'label'	=> __('Add Button Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_404_page',
		'setting'	=> 'vw_yoga_fitness_404_page_btn_icon',
		'type'		=> 'icon'
	)));

	//No Result Page Setting
	$wp_customize->add_section('vw_yoga_fitness_no_results_page',array(
		'title'	=> __('No Results Page Settings','vw-yoga-fitness'),
		'panel' => 'vw_yoga_fitness_other_panel_id',
	));

	$wp_customize->add_setting('vw_yoga_fitness_no_results_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('vw_yoga_fitness_no_results_page_title',array(
		'label'	=> __('Add Title','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Nothing Found', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_no_results_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_no_results_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('vw_yoga_fitness_no_results_page_content',array(
		'label'	=> __('Add Text','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_no_results_page',
		'type'=> 'text'
	));

	//Social Icon Setting
	$wp_customize->add_section('vw_yoga_fitness_social_icon_settings',array(
		'title'	=> __('Social Icons Settings','vw-yoga-fitness'),
		'panel' => 'vw_yoga_fitness_other_panel_id',
	));

	$wp_customize->add_setting('vw_yoga_fitness_social_icon_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_social_icon_font_size',array(
		'label'	=> __('Icon Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_social_icon_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_social_icon_padding',array(
		'label'	=> __('Icon Padding','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_social_icon_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_social_icon_width',array(
		'label'	=> __('Icon Width','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_social_icon_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_social_icon_height',array(
		'label'	=> __('Icon Height','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_social_icon_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_social_icon_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_social_icon_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Responsive Media Settings
	$wp_customize->add_section('vw_yoga_fitness_responsive_media',array(
		'title'	=> __('Responsive Media','vw-yoga-fitness'),
		'panel' => 'vw_yoga_fitness_other_panel_id',
	));

    $wp_customize->add_setting( 'vw_yoga_fitness_stickyheader_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_stickyheader_hide_show',array(
      'label' => esc_html__( 'Show / Hide Sticky Header','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_responsive_media'
    )));

    $wp_customize->add_setting( 'vw_yoga_fitness_resp_slider_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_resp_slider_hide_show',array(
      'label' => esc_html__( 'Show / Hide Slider','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_responsive_media'
    )));

    $wp_customize->add_setting( 'vw_yoga_fitness_sidebar_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_sidebar_hide_show',array(
      'label' => esc_html__( 'Show / Hide Sidebar','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_responsive_media'
    )));

    $wp_customize->add_setting( 'vw_yoga_fitness_resp_scroll_top_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_resp_scroll_top_hide_show',array(
      'label' => esc_html__( 'Show / Hide Scroll To Top','vw-yoga-fitness' ),
      'section' => 'vw_yoga_fitness_responsive_media'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_resp_menu_toggle_btn_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_yoga_fitness_resp_menu_toggle_btn_bg_color', array(
		'label'    => __('Toggle Button Bg Color', 'vw-yoga-fitness'),
		'section'  => 'vw_yoga_fitness_responsive_media',
	)));

    $wp_customize->add_setting('vw_yoga_fitness_res_menu_open_icon',array(
		'default'	=> 'fas fa-bars',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_res_menu_open_icon',array(
		'label'	=> __('Add Open Menu Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_responsive_media',
		'setting'	=> 'vw_yoga_fitness_res_menu_open_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('vw_yoga_fitness_res_menu_close_icon',array(
		'default'	=> 'fas fa-times',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new VW_Yoga_Fitness_Fontawesome_Icon_Chooser(
        $wp_customize,'vw_yoga_fitness_res_menu_close_icon',array(
		'label'	=> __('Add Close Menu Icon','vw-yoga-fitness'),
		'transport' => 'refresh',
		'section'	=> 'vw_yoga_fitness_responsive_media',
		'setting'	=> 'vw_yoga_fitness_res_menu_close_icon',
		'type'		=> 'icon'
	)));


    //Woocommerce settings
	$wp_customize->add_section('vw_yoga_fitness_woocommerce_section', array(
		'title'    => __('WooCommerce Layout', 'vw-yoga-fitness'),
		'priority' => null,
		'panel'    => 'woocommerce',
	));

    //Shop Page Featured Image
	$wp_customize->add_setting( 'vw_yoga_fitness_shop_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_shop_featured_image_border_radius', array(
		'label'       => esc_html__( 'Shop Page Featured Image Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'vw_yoga_fitness_shop_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_shop_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Shop Page Featured Image Box Shadow','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'vw_yoga_fitness_woocommerce_shop_page_sidebar', array( 'selector' => '.post-type-archive-product .sidebar',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_woocommerce_shop_page_sidebar', ) );

    //Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'vw_yoga_fitness_woocommerce_shop_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_woocommerce_shop_page_sidebar',array(
		'label' => esc_html__( 'Shop Page Sidebar','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_woocommerce_section'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_shop_page_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_shop_page_layout',array(
        'type' => 'select',
        'label' => __('Shop Page Sidebar Layout','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_woocommerce_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-yoga-fitness'),
            'Right Sidebar' => __('Right Sidebar','vw-yoga-fitness'),
        ),
	) );

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'vw_yoga_fitness_woocommerce_single_product_page_sidebar', array( 'selector' => '.single-product .sidebar',
		'render_callback' => 'vw_yoga_fitness_customize_partial_vw_yoga_fitness_woocommerce_single_product_page_sidebar', ) );

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'vw_yoga_fitness_woocommerce_single_product_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Single Product Sidebar','vw-yoga-fitness' ),
		'section' => 'vw_yoga_fitness_woocommerce_section'
    )));

    $wp_customize->add_setting('vw_yoga_fitness_single_product_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_single_product_layout',array(
        'type' => 'select',
        'label' => __('Single Product Sidebar Layout','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_woocommerce_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-yoga-fitness'),
            'Right Sidebar' => __('Right Sidebar','vw-yoga-fitness'),
        ),
	) ); 

    //Related Products
	$wp_customize->add_setting( 'vw_yoga_fitness_related_product_show_hide',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_related_product_show_hide',array(
        'label' => esc_html__( 'Related product','vw-yoga-fitness' ),
        'section' => 'vw_yoga_fitness_woocommerce_section'
    )));

    //Products per page
    $wp_customize->add_setting('vw_yoga_fitness_products_per_page',array(
		'default'=> '9',
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_float'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_per_page',array(
		'label'	=> __('Products Per Page','vw-yoga-fitness'),
		'description' => __('Display on shop page','vw-yoga-fitness'),
		'input_attrs' => array(
            'step'             => 1,
			'min'              => 0,
			'max'              => 50,
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'number',
	));

    //Products per row
    $wp_customize->add_setting('vw_yoga_fitness_products_per_row',array(
		'default'=> '3',
		'sanitize_callback'	=> 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_per_row',array(
		'label'	=> __('Products Per Row','vw-yoga-fitness'),
		'description' => __('Display on shop page','vw-yoga-fitness'),
		'choices' => array(
            '2' => '2',
			'3' => '3',
			'4' => '4',
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'select',
	));

	//Products padding
	$wp_customize->add_setting('vw_yoga_fitness_products_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_padding_top_bottom',array(
		'label'	=> __('Products Padding Top Bottom','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_products_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_padding_left_right',array(
		'label'	=> __('Products Padding Left Right','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	//Products box shadow
	$wp_customize->add_setting( 'vw_yoga_fitness_products_box_shadow', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_products_box_shadow', array(
		'label'       => esc_html__( 'Products Box Shadow','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Products border radius
    $wp_customize->add_setting( 'vw_yoga_fitness_products_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_products_border_radius', array(
		'label'       => esc_html__( 'Products Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('vw_yoga_fitness_products_btn_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_btn_padding_top_bottom',array(
		'label'	=> __('Products Button Padding Top Bottom','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_products_btn_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_products_btn_padding_left_right',array(
		'label'	=> __('Products Button Padding Left Right','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_products_button_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_products_button_border_radius', array(
		'label'       => esc_html__( 'Products Button Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Products Sale Badge
	$wp_customize->add_setting('vw_yoga_fitness_woocommerce_sale_position',array(
        'default' => 'right',
        'sanitize_callback' => 'vw_yoga_fitness_sanitize_choices'
	));
	$wp_customize->add_control('vw_yoga_fitness_woocommerce_sale_position',array(
        'type' => 'select',
        'label' => __('Sale Badge Position','vw-yoga-fitness'),
        'section' => 'vw_yoga_fitness_woocommerce_section',
        'choices' => array(
            'left' => __('Left','vw-yoga-fitness'),
            'right' => __('Right','vw-yoga-fitness'),
        ),
	) );

	$wp_customize->add_setting('vw_yoga_fitness_woocommerce_sale_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_woocommerce_sale_font_size',array(
		'label'	=> __('Sale Font Size','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_woocommerce_sale_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_woocommerce_sale_padding_top_bottom',array(
		'label'	=> __('Sale Padding Top Bottom','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_yoga_fitness_woocommerce_sale_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_yoga_fitness_woocommerce_sale_padding_left_right',array(
		'label'	=> __('Sale Padding Left Right','vw-yoga-fitness'),
		'description'	=> __('Enter a value in pixels. Example:20px','vw-yoga-fitness'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'vw-yoga-fitness' ),
        ),
		'section'=> 'vw_yoga_fitness_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_yoga_fitness_woocommerce_sale_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'vw_yoga_fitness_sanitize_number_range'
	) );
	$wp_customize->add_control( 'vw_yoga_fitness_woocommerce_sale_border_radius', array(
		'label'       => esc_html__( 'Sale Border Radius','vw-yoga-fitness' ),
		'section'     => 'vw_yoga_fitness_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

  	// Related Product
    $wp_customize->add_setting( 'vw_yoga_fitness_related_product_show_hide',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_yoga_fitness_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Yoga_Fitness_Toggle_Switch_Custom_Control( $wp_customize, 'vw_yoga_fitness_related_product_show_hide',array(
        'label' => esc_html__( 'Related product','vw-yoga-fitness' ),
        'section' => 'vw_yoga_fitness_woocommerce_section'
    )));

    // Has to be at the top
	$wp_customize->register_panel_type( 'VW_Yoga_Fitness_WP_Customize_Panel' );
	$wp_customize->register_section_type( 'VW_Yoga_Fitness_WP_Customize_Section' );
}

add_action( 'customize_register', 'vw_yoga_fitness_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

if ( class_exists( 'WP_Customize_Panel' ) ) {
  	class VW_Yoga_Fitness_WP_Customize_Panel extends WP_Customize_Panel {
	    public $panel;
	    public $type = 'vw_yoga_fitness_panel';
	    public function json() {

			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;
			return $array;
	    }
  	}
}

if ( class_exists( 'WP_Customize_Section' ) ) {
  	class VW_Yoga_Fitness_WP_Customize_Section extends WP_Customize_Section {
	    public $section;
	    public $type = 'vw_yoga_fitness_section';
	    public function json() {

			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			if ( $this->panel ) {
			$array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
			} else {
			$array['customizeAction'] = 'Customizing';
			}
			return $array;
		}
  	}
}

// Enqueue our scripts and styles
function vw_yoga_fitness_customize_controls_scripts() {
	wp_enqueue_script( 'customizer-controls', get_theme_file_uri( '/assets/js/customizer-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'vw_yoga_fitness_customize_controls_scripts' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class VW_Yoga_Fitness_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'VW_Yoga_Fitness_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(new VW_Yoga_Fitness_Customize_Section_Pro($manager,'vw_yoga_fitness_upgrade_pro_link',array(
			'priority'   => 1,
			'title'    => esc_html__( 'VW Yoga Fitness', 'vw-yoga-fitness' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'vw-yoga-fitness' ),
				'pro_url'  => esc_url('https://www.vwthemes.com/themes/yoga-wordpress-theme/'),
		)));

		// Register sections.
		$manager->add_section(new VW_Yoga_Fitness_Customize_Section_Pro($manager,'vw_yoga_fitness_get_started_link',array(
			'priority'   => 1,
			'title'    => esc_html__( 'DOCUMENTATION', 'vw-yoga-fitness' ),
			'pro_text' => esc_html__( 'DOCS', 'vw-yoga-fitness' ),
			'pro_url'  => esc_url('https://www.vwthemesdemo.com/docs/free-vw-yoga-fitness/'),
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'vw-yoga-fitness-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'vw-yoga-fitness-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/css/customize-controls.css' );

		wp_localize_script(
		'vw-yoga-fitness-customize-controls',
		'vw_yoga_fitness_customizer_params',
		array(
			'ajaxurl' =>	admin_url( 'admin-ajax.php' )
		));
	}
}

// Doing this customizer thang!
VW_Yoga_Fitness_Customize::get_instance();
