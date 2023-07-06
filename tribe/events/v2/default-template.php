<?php
/**
 * Default Events Template
 *
 * @package TribeEventsCalendar
 * @version 4.6.23
 *
 */

use TEC\Events_Pro\Custom_Tables\V1\Templates\Series_Filters;
use Tribe\Events\Views\V2\Template_Bootstrap;

if (!defined('ABSPATH')) {
    die('-1');
}

$context = Timber::get_context();

if (is_singular('tribe_event_series')) {
    $series_filters = new Series_Filters();
    $context['tribe_markup'] = $series_filters->inject_content('');
} else {
    $context['tribe_markup'] = tribe(Template_Bootstrap::class)->get_view_html();
}

Timber::render(array('events.twig'), $context);
