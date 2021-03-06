<?php
/**
 * Template Name: Sidebar
 */
$queried_object = get_queried_object();

$context = Timber::get_context();
$post = new TimberPost();

if ( $post->post_parent ) {
	$ancestors = get_post_ancestors($post->ID);
	$root = count( $ancestors ) - 1;
	$parent = $ancestors[$root];
}
else {
    $parent = $post->ID;
}

$parent_page_args = array(
    'post_type'         => 'page',
    'page_id'           => $parent,
);
$parent_page = Timber::get_posts( $parent_page_args );

$other_page_args = array(
    'post_type'         => 'page',
    'post_parent'       => $parent,
    'posts_per_page'    => -1,
    'order'             => 'ASC',
    'orderby'           => 'menu_order'
);
$other_pages = Timber::get_posts( $other_page_args );

$menu_items = array_merge( $parent_page, $other_pages );

$depth = spm_get_current_page_depth();
if ( $depth == 3 ) :
    // Great grandchild
    $current = wp_get_post_parent_id( wp_get_post_parent_id( $queried_object->ID ) );
elseif ( $depth == 2 ) :
    // Grandchild
    $current = wp_get_post_parent_id( $queried_object->ID );
else :
    // Parent or child
    $current = $queried_object->ID;
endif;

$other_grandchildren_args = array(
    'post_type'         => 'page',
    'post_parent'       => $current,
    'posts_per_page'    => -1,
    'order'             => 'ASC',
    'orderby'           => 'menu_order'
);

$other_grandchildren = Timber::get_posts( $other_grandchildren_args );

$immediate_parent = Timber::get_post( $current );

$context['post'] = $post;
$context['template'] = 'sidebar';
$context['menu_items'] = $menu_items;
$context['current'] = $current;
$context['title'] = $post->name;
$context['depth'] = $depth;
$context['immediate_parent'] = $immediate_parent;
$context['other_grandchildren'] = $other_grandchildren;

Timber::render( array( 'custom-sidebar.twig' ), $context );