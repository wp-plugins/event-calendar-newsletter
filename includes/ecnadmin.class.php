<?php

define( 'ECN_VERSION', 1 );

class ECNAdmin {
    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
        if ( is_admin() ) {
            add_action( 'admin_init', array( &$this, 'admin_init' ) );
            add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
            add_action( 'wp_ajax_fetch_events', array( &$this, 'ajax_fetch_events' ) );
            if ( isset( $_GET['page'] ) and 'eventcalendarnewsletter' == $_GET['page'] ) {
                add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
            }
        }
    }

    function init() {
    }

    function admin_init() {
    }

    function enqueue_scripts() {
        wp_register_script( 'ecn.admin.js', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'backbone', 'underscore', 'jquery-ui-core', 'jquery-ui-sortable' ), ECN_VERSION );
        wp_enqueue_script( 'ecn.admin.js' );
        wp_register_Style( 'ecn.admin.css', plugins_url( 'css/admin.css', __FILE__ ), false, ECN_VERSION );
        wp_enqueue_style( 'ecn.admin.css' );
    }

    function admin_menu() {
        add_menu_page( 'Event Calendar Newsletter', 'Event Calendar Newsletter', 'add_users', 'eventcalendarnewsletter', array( &$this, 'admin_page' ), null, 41 );
    }

    function get_available_calendar_feeds() {
        $available_feed_objects = ECNCalendarFeedFactory::get_available_calendar_feeds();
        $available_feeds = array();
        foreach ( $available_feed_objects as $feed_object ) {
            $available_feeds[$feed_object->get_identifier()] = $feed_object->get_description();
        }
        return $available_feeds;
    }

    private function get_ecn_options() {
        return get_option( 'ecn_saved_options', array() );
    }

    private function get_ecn_option( $option_name, $default = '' ) {
        $ecn_options = $this->get_ecn_options();
        if ( isset( $ecn_options[$option_name] ) )
            return $ecn_options[$option_name];
        else
            return $default;
    }

    private function save_ecn_options( $options ) {
        if ( ! is_array( $options ) )
            throw new Exception( 'Invalid options array' );
        add_option( 'ecn_saved_options', $options );
        update_option( 'ecn_saved_options', $options );
    }

    private function save_ecn_option( $option_name, $value ) {
        $options = $this->get_ecn_options();
        $options[$option_name] = $value;
        $this->save_ecn_options( $options );
    }

    private function get_saved_format() {
        return $this->get_ecn_option( 'saved_format', "<h2>{title}</h2>
<p>{start_date} @ {start_time} to {end_time} at {location_name}</p>
<p>{description}</p>" );
    }

    private function save_format( $format ) {
        $this->save_ecn_option( 'saved_format', $format );
    }

    private function get_future_events_to_use() {
        return $this->get_ecn_option( 'saved_future_events_to_use', 1 );
    }

    private function save_future_events_to_use( $future_events_to_use_in_months ) {
        $this->save_ecn_option( 'saved_future_events_to_use', $future_events_to_use_in_months );
    }

    private function get_event_calendar_plugin() {
        return $this->get_ecn_option( 'event_calendar_plugin' );
    }

    private function save_event_calendar_plugin( $plugin ) {
        $this->save_ecn_option( 'event_calendar_plugin', $plugin );
    }

    function ajax_fetch_events() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ecn_admin' ) )
            die();
        $months = intval( $_POST['events_future_in_months'] );
        $event_calendar_identifier = $_POST['event_calendar'];
        $format = $_POST['format'];
        try {
            $this->save_format( $format );
            $this->save_event_calendar_plugin( $event_calendar_identifier );
            $this->save_future_events_to_use( $months );
            $feed = ECNCalendarFeedFactory::create( $event_calendar_identifier );
            $start_date = strtotime( date( 'Y-m-d' ) );
            $end_date_format = 'now +' . $months . ' month' . ( $months > 1 ? 's' : '' );
            $end_date = strtotime( $end_date_format );
            $events = $feed->get_events( $start_date, $end_date );
            $output = '';
            $count = 0;
            foreach ( $events as $event ) {
                $output .= "\n" . $event->get_from_format( $format );
                $count++;
                if ( $count > 100 )
                    break;
            }
            echo json_encode( array( 'success' => true, 'result' => $output ) );
            die();
        } catch ( Exception $e ) {
            echo json_encode( array( 'error' => true, 'message' => $e->getMessage() ) );
            throw $e;
        }

    }

    function admin_page() {
        $format = $this->get_saved_format();
        $future_events_in_months = $this->get_future_events_to_use();
        $saved_plugin = $this->get_event_calendar_plugin();
        $available_plugins = $this->get_available_calendar_feeds();
        ?>
        <div class="wrap">
            <h2>Event Calendar Newsletter</h2>
            <?php if ( ! $available_plugins ): ?>
                <h3>No supported event calendar plugins available.</h3>
                <p>Supported calendars include <a href="https://wordpress.org/plugins/the-events-calendar/">The Events Calendar</a> and <a href="http://wordpress.org/plugins/ajax-event-calendar/">Ajax Event Calendar</a></p>
                <p>Have an events calendar you'd like supported?  <a href="mailto:brian@brianhogg.ca">Let me know</a>!</p>
            <?php else: ?>
                <div id="ecn-admin">
                    <?php wp_nonce_field( 'ecn_admin', 'wp_ecn_admin_nonce' ); ?>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Event Calendar:</th>
                                <td>
                                    <select name="event_calendar">
                                        <?php foreach ( $available_plugins as $plugin => $description ): ?>
                                            <option value="<?php echo esc_attr( $plugin ); ?>"<?php echo ( $plugin == $saved_plugin ? ' SELECTED' : '' ); ?>><?php echo esc_html( $description ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div>
                                        <em>Can't find the calendar with your events that you'd like to use?  <a href="mailto:brian@brianhogg.com">Let me know</a>!
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Future Events to Use:</th>
                                <td>
                                    <select name="events_future_in_months">
                                        <?php for ( $i = 1; $i <= 12; $i++ ): ?>
                                            <option value="<?php echo $i; ?>"<?php echo ( $i == $future_events_in_months ? ' SELECTED' : '' ); ?>><?php echo $i; ?> month<?php echo ( $i == 1 ? '' : 's' ); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Format:</th>
                                <td>
                                    <div>
                                        <select id="placeholder">
                                            <?php foreach ( ECNCalendarEvent::get_available_format_tags() as $tag => $description ): ?>
                                                <option value="<?php echo esc_attr( $tag ); ?>"><?php echo esc_html( $description ); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input id="insert_placeholder" type="submit" value="Insert" class="button" />
                                    </div>
                                    <div>
                                        <textarea name="format" rows="10" cols="80"><?php echo esc_html( $format ); ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input id="fetch_events" type="submit" value="Generate Newsletter Formatted Events" class="button button-primary" />
                                    <img src="<?php echo admin_url( '/images/loading.gif' ); ?>" class="loading" />
                                </td>
                            </tr>
                            <tr class="result">
                                <th scope="row">Result:</th>
                                <td>
                                    <div id="output"></div>
                                </td>
                            </tr>
                            <tr class="result">
                                <th scope="row">Result (HTML):</th>
                                <td>
                                    <textarea id="output_html" rows="10" cols="80"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }
}

$GLOBALS['ecn_admin_class'] = new ECNAdmin();