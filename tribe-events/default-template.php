<?php
/** * Default Events Template * This file is the basic wrapper template for all the views if 'Default Events Template' * is selected in Events -> Settings -> Template -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 *
 */

$context = Timber::get_context();
Timber::render('templates/events.twig', $context);