<?php
/**
 * Template Name: Home
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'page.twig' ), $context );