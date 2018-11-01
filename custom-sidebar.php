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

$context['post'] = $post;
$context['template'] = 'sidebar';
$context['menu_items'] = $menu_items;
$context['current'] = $queried_object->ID;
$context['title'] = $post->name;

Timber::render( array( 'custom-sidebar.twig' ), $context );