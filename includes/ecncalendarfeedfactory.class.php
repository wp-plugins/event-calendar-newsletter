<?php
class ECNCalendarFeedFactory {
    private static $_plugins = array(
        'AjaxCalendar',
        'TheEventsCalendar',
    );

    public static function create( $identifier ) {
        foreach ( self::get_available_calendar_feeds() as $feed ) {
            if ( $identifier == $feed->get_identifier() )
                return $feed;
        }
        throw new Exception( 'Invalid identifier ' . $identifier );
    }

    /**
     * Return the available calendar feeds
     *
     * @return ECNCalendarFeed[]
     */
    public static function get_available_calendar_feeds() {
        $retval = array();
        foreach ( self::$_plugins as $plugin ) {
            $class_name = "ECNCalendarFeed$plugin";
            $feed = new $class_name;
            if ( $feed->is_feed_available() )
                $retval[] = $feed;
        }
        return $retval;
    }
}