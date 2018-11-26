<?php

$context = array();

$categories = get_categories( array(
    'orderby' => 'name',
    'order'   => 'ASC',
    'hide_empty' => '1'
) );
    
$context['categories'] = $categories;

Timber::render('sidebar-news.twig', $context);