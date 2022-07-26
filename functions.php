<?php

function spm_get_breadcrumbs( $post, $displayCurrent ) {
	$count = 1;
	$postAncestors = get_post_ancestors( $post );
	$sortedAncestorArray = array();
	
	foreach ( $postAncestors as $ancestor ) {
		$sortedAncestorArray[] = $ancestor;
	}
	krsort( $sortedAncestorArray ); // Sort an array by key in reverse order
	
	echo "<ul class='breadcrumbs'>";

	foreach ( $sortedAncestorArray as $ancestor ) {
		echo "<li class='breadcrumb'><a class='breadcrumb-link-". $count ."' href='". esc_url(get_permalink($ancestor)) ."' title='". get_the_title($ancestor) ."'>". get_the_title($ancestor) ."</a></li>";
		$count++;
	}

	if( $displayCurrent ) {
		echo "<li class='breadcrumb'>". get_the_title($post) ."</li>";
	}

	echo "</ul>";
}


function spm_get_current_page_depth(){
    global $wp_query;
     
    $object = $wp_query->get_queried_object();
    $parent_id  = $object->post_parent;
    $depth = 0;
    while($parent_id > 0){
        $page = get_page($parent_id);
        $parent_id = $page->post_parent;
        $depth++;
    }
  
    return $depth;
}

function spm_is_local() {
	$local_ip_addresses = [
		// IPv4 address
		'127.0.0.1', 
	
		// IPv6 address
		'::1',

		// Local by Flywheel
		'172.17.0.1',
	];

	$is_local = true;

	if ( !in_array( $_SERVER['REMOTE_ADDR'], $local_ip_addresses ) ) {
		$is_local = false;
	}

	return $is_local;
}

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_post_type_support( 'page', 'excerpt' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'spm_create_options_pages' ) );
		add_action( 'init', array( $this, 'spm_register_nav_menus' ) );
		add_action( 'init', array( $this, 'spm_register_post_types' ) );
		add_action( 'init', array( $this, 'spm_register_taxonomies' ) );
		add_filter( 'upload_mimes', array( $this, 'cc_mime_types' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'spm_enqueue' ) );
		
		parent::__construct();
	}

	function cc_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	
	function spm_create_options_pages() {
		//this is where you can create ACF options pages
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page();
		}
	}

	function spm_register_nav_menus() {
		//this is where you can register custom nav menus
		register_nav_menus( array(
			'header' => 'Header',
			'footer' => 'Footer',
		) );
	}

	function spm_register_post_types() {
		//this is where you can register custom post types
	}

	function spm_register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function spm_enqueue() {
		//this is where you can enqueue styles and scripts
		wp_enqueue_style( 'spm-css', get_template_directory_uri() . '/static/css/spm.css' );
		wp_enqueue_style( 'cabin', '//fonts.googleapis.com/css?family=Cabin:400,400i,600,600i,700' );
		wp_enqueue_style( 'font-awesome', '//use.fontawesome.com/releases/v5.3.1/css/all.css' );

		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/static/js/bootstrap.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'spm-js', get_template_directory_uri() . '/static/js/spm.js', array( 'jquery' ) );
    }

	function add_to_context( $context ) {
		$context['categories'] = Timber::get_terms('category');
		$context['header'] = new TimberMenu( 'header' );
		$context['footer'] = new TimberMenu( 'footer' );
		$context['options'] = get_fields('option');
		$context['site'] = $this;

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => 10,
			'orderby' => array(
				'date' => 'DESC'
			)
		);
		$context['blog_posts'] = Timber::get_posts( $args );

		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}
 
}  

new StarterSite();

// GF: Enable legacy markup
add_filter('gform_enable_legacy_markup', '__return_true');
