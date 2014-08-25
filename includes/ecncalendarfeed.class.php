<?php

abstract class ECNCalendarFeed {

    protected $REPEAT_DAY;
    protected $REPEAT_WEEK;
    protected $REPEAT_MONTH;
    protected $REPEAT_YEAR;

    /**
     * Translate local feed frequency into
     * @param $frequency
     * @return string
     */
    protected function get_repeat_frequency_from_feed_frequency( $frequency ) {
        switch ( $frequency ) {
            case $this->REPEAT_DAY:
                return ECNCalendarEvent::REPEAT_DAY;
            case $this->REPEAT_WEEK:
                return ECNCalendarEvent::REPEAT_WEEK;
            case $this->REPEAT_MONTH:
                return ECNCalendarEvent::REPEAT_MONTH;
            case $this->REPEAT_YEAR:
                return ECNCalendarEvent::REPEAT_YEAR;
        }
        return false;
    }

    /**
     * Fetch events in the given date range
     *
     * @param $start_date
     * @param $end_date
     * @return ECNCalendarEvent[]
     */
    abstract function get_events( $start_date, $end_date );

    /**
     * Fetch description for this calendar feed
     *
     * @return string
     */
    abstract function get_description();

    /**
     * Fetch unique identifier for this calendar feed
     *
     * @return string
     */
    abstract function get_identifier();
}