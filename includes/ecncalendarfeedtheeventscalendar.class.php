<?php

class ECNCalendarFeedTheEventsCalendar extends ECNCalendarFeed {
    /**
     * @param $start_date int
     * @param $end_date int
     * @return ECNCalendarEvent[]
     */
    function get_events( $start_date, $end_date ) {
        global $post;
        $retval = array();
        $query = TribeEventsQuery::getEvents( array( 'posts_per_page' => 100 ), true );

        while ( $query->have_posts() ) {
            $query->the_post();
            $event = $post;
            do_action( 'tribe_events_inside_before_loop' );

            if ( strtotime( $event->EventStartDate ) < $start_date )
                continue;
            if ( strtotime( $event->EventEndDate ) > $end_date )
                break;
            $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
            if ( !empty( $image_src ) )
                $image_url = $image_src[0];
            else
                $image_url = false;

            $retval[] = new ECNCalendarEvent( array(
                'start_date' => $event->EventStartDate,
                'end_date' => $event->EventEndDate,
                'title' => stripslashes_deep( $event->post_title ),
                'description' => stripslashes_deep( $event->post_content ),
                'excerpt' => stripslashes_deep( $event->post_excerpt ),
                'location_name' => tribe_get_venue(),
                'location_address' => tribe_get_address(),
                'location_city' => tribe_get_city(),
                'location_state' => tribe_get_state(),
                'location_zip' => tribe_get_zip(),
                'location_country' => tribe_get_country(),
                'contact_name' => tribe_get_organizer(),
                'contact_email' => tribe_get_organizer_email(),
                'contact_website' => tribe_get_organizer_website_url(),
                'contact_phone' => tribe_get_organizer_phone(),
                'link' => get_the_permalink(),
                'event_image_url' => $image_url,
                'event_cost' => tribe_get_cost(),
                'all_day' => tribe_event_is_all_day(),
            ) );
            do_action( 'tribe_events_inside_after_loop' );
        }
        return $retval;
    }

    function get_description() {
        return 'The Events Calendar';
    }

    function get_identifier() {
        return 'the-events-calendar';
    }

    function is_feed_available() {
        return is_plugin_active( 'the-events-calendar/the-events-calendar.php' );
    }
}