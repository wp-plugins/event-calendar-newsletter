<?php
/*
Plugin Name: Event Calendar Newsletter
Plugin URI: http://wordpress.org/extend/plugins/event-calendar-newsletter/
Description: A plugin that fetches events from common event calendar plugins, and outputs newsletter-friendly text
Version: 1.1
Author: Brian Hogg
Author URI: http://brianhogg.com
License: GPL2
*/

/*  Copyright 2014  Brian Hogg <email: brian@brianhogg.ca>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("No script kiddies please!");

require_once( dirname( __FILE__ ) . '/includes/ecnadmin.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecncalendarevent.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecncalendarfeed.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecncalendarfeedfactory.class.php' );

// Supported plugins
require_once( dirname( __FILE__ ) . '/includes/ecncalendarfeedajaxcalendar.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecncalendarfeedtheeventscalendar.class.php' );
