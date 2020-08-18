<?php

include_once('events.php');

function acme_shortcode_init(){
  
    function acme_events_display($atts){

        //Set the shortcode attributes and defaults
        $a = shortcode_atts( array(
            'start' => 'something',
            'end' => 'something else',
            'limit' => '5',
            'cal' => true,
        ), $atts );

        //Output our loop
        // foreach ((array) $events as $event) {
        //     $evlist = '<div style="background:' . $event['colorCategory']['backgroundColor'] . ';">';
        //     $evlist .= '<h3>' . $event['name'] . '</h1>';
        //     $evlist .= '<p>' . $event['id'] . '</p>';
        //     $evlist .= $event['description'] . '<br/>';
        //     $evlist .= $event['startTime'];
        //     $evlist .= '<h5>Price: $' . $event['priceList']['prices'][0]['price'] . '</h5>';
        //     $evlist .= '<a href="https://sand1-buy.acmeticketing.net/events/10002/detail/' . $event['id'] . '"><h2>View Event</h2></a>';
        //     $evlist .= '</div>';
        // }
        // return $evlist;
        
    return $events;
    }

    add_shortcode("acme_events", "acme_events_display");

}

add_action('init', 'acme_shortcode_init');

?>