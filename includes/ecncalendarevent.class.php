<?php
class ECNCalendarEvent {
    private $_start_date;
    private $_end_date;
    private $_description;
    private $_title;
    private $_location_name;
    private $_location_address;
    private $_location_city;
    private $_location_zip;
    private $_location_state;
    private $_location_country;
    private $_location_website;
    private $_contact_name;
    private $_contact_info;
    private $_contact_website;
    private $_contact_email;
    private $_contact_phone;
    private $_event_website;
    private $_event_cost;
    private $_excerpt;
    private $_event_image_url;
    private $_all_day;
    private $_link;
    private $_repeat_frequency;
    private $_repeat_interval;
    private $_repeat_end;

    const REPEAT_DAY = 'day';
    const REPEAT_WEEK = 'week';
    const REPEAT_MONTH = 'month';
    const REPEAT_YEAR = 'year';

    public function __construct( array $args = array() ) {
        if ( isset( $args['start_date'] ) )
            $this->set_start_date( $args['start_date'] );
        if ( isset( $args['end_date'] ) )
            $this->set_end_date( $args['end_date'] );
        if ( isset( $args['description'] ) )
            $this->set_description( $args['description'] );
        if ( isset( $args['title'] ) )
            $this->set_title( $args['title'] );
        if ( isset( $args['location_name'] ) )
            $this->set_location_name( $args['location_name'] );
        if ( isset( $args['location_address'] ) )
            $this->set_location_address( $args['location_address'] );
        if ( isset( $args['location_city'] ) )
            $this->set_location_city( $args['location_city'] );
        if ( isset( $args['location_state'] ) )
            $this->set_location_state( $args['location_state'] );
        if ( isset( $args['location_zip'] ) )
            $this->set_location_zip( $args['location_zip'] );
        if ( isset( $args['location_country'] ) )
            $this->set_location_country( $args['location_country'] );
        if ( isset( $args['location_website'] ) )
            $this->set_location_website( $args['location_website'] );
        if ( isset( $args['contact_name'] ) )
            $this->set_contact_name( $args['contact_name'] );
        if ( isset( $args['contact_info'] ) )
            $this->set_contact_info( $args['contact_info'] );
        if ( isset( $args['contact_website'] ) )
            $this->set_contact_website( $args['contact_website'] );
        if ( isset( $args['contact_phone'] ) )
            $this->set_contact_phone( $args['contact_phone'] );
        if ( isset( $args['contact_email'] ) )
            $this->set_contact_email( $args['contact_email'] );
        if ( isset( $args['all_day'] ) )
            $this->set_all_day( $args['all_day'] );
        if ( isset( $args['link'] ) )
            $this->set_link( $args['link'] );
        if ( isset( $args['event_cost'] ) )
            $this->set_event_cost( $args['event_cost'] );
        if ( isset( $args['event_website'] ) )
            $this->set_event_website( $args['event_website'] );
        if ( isset( $args['excerpt'] ) )
            $this->set_excerpt( $args['excerpt'] );
        if ( isset( $args['event_image_url'] ) )
            $this->set_event_image_url( $args['event_image_url'] );
        if ( isset( $args['repeat_frequency'] ) )
            $this->set_repeat_frequency( $args['repeat_frequency'] );
        if ( isset( $args['repeat_interval'] ) )
            $this->set_repeat_interval( $args['repeat_interval'] );
        if ( isset( $args['repeat_end'] ) )
            $this->set_repeat_end( $args['repeat_end'] );
    }

    public static function get_available_format_tags() {
        return array(
            'title' => 'Title',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'start_time' => 'Start Time',
            'end_date' => 'End Date',
            'end_time' => 'End Time',
            'location_name' => 'Location Name',
            'location_address' => 'Location Address',
            'location_city' => 'Location City',
            'location_state' => 'Location State',
            'location_zip' => 'Location Zip/Postal',
            'location_country' => 'Location Country',
            'location_website' => 'Location Website',
            'contact_name' => 'Contact Name',
            'contact_info' => 'Contact Info',
            'contact_phone' => 'Contact Phone',
            'contact_website' => 'Contact Website',
            'contact_email' => 'Contact Email',
            'event_cost' => 'Event Cost',
            'excerpt' => 'Excerpt',
            'event_image' => 'Event Image',
            'all_day' => 'All Day',
            'link' => 'Event Link',
            'recurring' => 'Recurring Description (if recurring)',
        );
    }
    
    private function sanitize_link( $link ) {
        return $link;
    }
    
    private function sanitize_date( $date ) {
        if ( is_numeric( $date ) )
            return $date;
        elseif ( strtotime( $date ) !== FALSE )
            return strtotime( $date );
        else
            throw new Exception( 'Invalid date' );
    }

    public function set_contact_phone( $contact_phone ) {
        $this->_contact_phone = $contact_phone;
    }

    public function get_contact_phone() {
        return $this->_contact_phone;
    }

    public function set_contact_website( $contact_website ) {
        $this->_contact_website = $contact_website;
    }

    public function get_contact_website() {
        return $this->_contact_website;
    }

    public function set_contact_email( $contact_email ) {
        $this->_contact_email = $contact_email;
    }

    public function get_contact_email() {
        return $this->_contact_email;
    }

    public function set_location_website( $location_website ) {
        $this->_location_website = $location_website;
    }

    public function get_location_website() {
        return $this->_location_website;
    }
    
    public function set_event_website( $event_website ) {
        $this->_event_website = $event_website;
    }

    public function get_event_website() {
        return $this->_event_website;
    }

    public function set_event_cost( $event_cost ) {
        $this->_event_cost = $event_cost;
    }

    public function get_event_cost() {
        return $this->_event_cost;
    }

    public function set_event_image_url( $event_image_url ) {
        $this->_event_image_url = $this->sanitize_link( $event_image_url );
    }

    public function get_event_image_url() {
        return $this->_event_image_url;
    }

    public function set_excerpt( $excerpt ) {
        $this->_excerpt = $excerpt;
    }

    public function get_excerpt() {
        return $this->_excerpt;
    }

    public function set_repeat_end( $repeat_end ) {
        $this->_repeat_end = $this->sanitize_date( $repeat_end );
    }

    public function get_repeat_end() {
        return date( 'Y-m-d', $this->_repeat_end );
    }

    public function set_repeat_interval( $repeat_interval ) {
        if ( ! in_array( $repeat_interval, array( self::REPEAT_DAY, self::REPEAT_MONTH, self::REPEAT_WEEK, self::REPEAT_YEAR ) ) )
            $this->_repeat_interval = false;
        $this->_repeat_interval = $repeat_interval;
    }

    public function get_repeat_interval() {
        return $this->_repeat_interval;
    }

    public function set_repeat_frequency( $repeat_frequency ) {
        $this->_repeat_frequency = $repeat_frequency;
    }

    public function get_repeat_frequency() {
        return $this->_repeat_frequency;
    }

    public function set_link( $link ) {
        $this->_link = $link;
    }

    public function get_link() {
        return $this->_link;
    }

    public function set_all_day( $all_day ) {
        $this->_all_day = $all_day ? true : false;
    }

    public function get_all_day() {
        return $this->_all_day;
    }

    public function set_contact_info( $contact_info ) {
        $this->_contact_info = $contact_info;
    }

    public function get_contact_info() {
        return $this->_contact_info;
    }

    public function set_contact_name( $contact_name ) {
        $this->_contact_name = $contact_name;
    }

    public function get_contact_name() {
        return $this->_contact_name;
    }

    public function set_location_country( $location_country ) {
        $this->_location_country = $location_country;
    }

    public function get_location_country() {
        return $this->_location_country;
    }

    public function set_location_zip( $location_zip ) {
        $this->_location_zip = $location_zip;
    }

    public function get_location_zip() {
        return $this->_location_zip;
    }

    public function set_location_state( $location_state ) {
        $this->_location_state = $location_state;
    }

    public function get_location_state() {
        return $this->_location_state;
    }

    public function set_location_city( $location_city ) {
        $this->_location_city = $location_city;
    }

    public function get_location_city() {
        return $this->_location_city;
    }

    public function set_location_name( $location_name ) {
        $this->_location_name = $location_name;
    }

    public function get_location_name() {
        return $this->_location_name;
    }

    public function set_location_address( $location_address ) {
        $this->_location_address = $location_address;
    }

    public function get_location_address() {
        return $this->_location_address;
    }

    public function set_description( $description ) {
        $this->_description = $description;
    }

    public function get_description() {
        return $this->_description;
    }

    public function set_title( $title ) {
        $this->_title = $title;
    }

    public function get_title() {
        return $this->_title;
    }

    public function set_start_date( $start_date ) {
        $this->_start_date = $this->sanitize_date( $start_date );
    }

    public function get_start_date() {
        return $this->_start_date;
    }

    public function set_end_date( $end_date ) {
        $this->_end_date = $this->sanitize_date( $end_date );
    }

    public function get_end_date() {
        return $this->_end_date;
    }

    public function get_from_format( $format ) {
        $retval = $format;
        foreach ( self::get_available_format_tags() as $tag => $description ) {
            switch ( $tag ) {
                case 'start_date':
                    $retval = str_replace( '{start_date}', date( 'l F jS', $this->get_start_date() ), $retval );
                    break;
                case 'start_time':
                    $retval = str_replace( '{start_time}', date( 'h:ia', $this->get_start_date() ), $retval );
                    break;
                case 'end_date':
                    $retval = str_replace( '{end_date}', date( 'l F jS', $this->get_end_date() ), $retval );
                    break;
                case 'end_time':
                    $retval = str_replace( '{end_time}', date( 'h:ia', $this->get_end_date() ), $retval );
                    break;
                case 'all_day':
                    $retval = str_replace( '{all_day}', 'All day', $retval );
                    break;
                case 'event_cost':
                    if ( $this->get_event_cost() )
                        $retval = str_replace( '{event_cost}', 'Cost: ' . $this->get_event_cost(), $retval );
                    else
                        $retval = str_replace( '{event_cost}', '', $retval );
                    break;
                case 'event_image':
                    if ( $this->get_event_image_url() )
                        $retval = str_replace( '{event_image}', '<img src="' . $this->get_event_image_url() . '" />', $retval );
                    else
                        $retval = str_replace( '{event_image}', '', $retval );
                    break;
                case 'recurring':
                    if ( $this->get_repeat_frequency() > 0 and $this->get_repeat_interval() )
                        $retval = str_replace( '{recurring}', 'Occurs every ' . $this->get_repeat_frequency() . ' ' . $this->get_repeat_interval() . ( $this->get_repeat_frequency() > 1 ? 's' : '' ), $retval );
                    else
                        $retval = str_replace( '{recurring}', '', $retval );
                    break;
                case 'link':
                    if ( $this->get_link() )
                        $retval = str_replace( '{link}', '<a href="' . $this->get_link() . '">'  . apply_filters( 'ecn_event_link_text', __( 'More information', 'ecn' ) ) . '</a>', $retval );
                    else
                        $retval = str_replace( '{link}', '', $retval );
                    break;
                default:
                    if ( method_exists( $this, "get_$tag" ) )
                        $retval = str_replace( '{' . $tag . '}', $this->{"get_$tag"}(), $retval );
            }
        }
        return $retval;
    }
}