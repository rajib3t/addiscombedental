<?php
/**
 * simple blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package simple_blog
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'simple_blog_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function simple_blog_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on simple blog, use a find and replace
		 * to change 'simple-blog' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'simple-blog', get_template_directory() . '/languages' );

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
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'simple-blog' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'simple_blog_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'simple_blog_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function simple_blog_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'simple_blog_content_width', 640 );
}
add_action( 'after_setup_theme', 'simple_blog_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function simple_blog_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'simple-blog' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'simple-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'simple_blog_widgets_init' );
if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js', false, null,true);
   wp_enqueue_script('jquery');
}
/**
 * Enqueue scripts and styles.
 */
function simple_blog_scripts() {
	wp_enqueue_style( 'simple-blog-style', get_stylesheet_uri(), array(), _S_VERSION );

	wp_style_add_data( 'simple-blog-style', 'rtl', 'replace' );
	wp_enqueue_style( 'googleapis-css', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Raleway:wght@300;400;500;600;700;900&display=swap' );
	wp_enqueue_style( 'bootstrap-min-css', get_template_directory_uri().'/css/bootstrap.min.css' );
	wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'style-css', get_template_directory_uri().'/css/style.css' );
	wp_enqueue_style( 'responsive-css', get_template_directory_uri().'/css/responsive.css' );

	wp_enqueue_script( 'simple-blog-popper',  'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'simple-blog-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), _S_VERSION, true );

	
}
add_action( 'wp_enqueue_scripts', 'simple_blog_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

require_once get_template_directory().'/inc/theme-admin.php';
require_once get_template_directory().'/inc/mega-menu.php';


/*Contact form 7 remove span*/
// add_filter('wpcf7_form_elements', function($content) {
//     $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

//     $content = str_replace('<br />', '', $content);
        
//     return $content;
// });

function link_words( $text ) {
	$replace = array(
		'http:'=>'https:',
		'<a href="#" class="btn btn_theme mx-auto mt-4">Make an Appointment</a>'=>'<a href="#" class="btn btn_theme mx-auto mt-4" data-toggle="modal" data-target="#myModal">Make an Appointment</a>'
		);
	$text = str_replace( array_keys($replace), $replace, $text );
	return $text;
}
add_filter( 'the_content', 'link_words' );
add_filter( 'the_excerpt', 'link_words' );
