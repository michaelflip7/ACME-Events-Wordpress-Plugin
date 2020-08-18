<?php
/**
* Plugin Name: ACME Events API
* Plugin URI: https://blucreative.ca
* Description: API integration for the ACME Events system. Uses shortcodes to display event widgets
* Version: 1.0
* Author: Blu Creative
* Author URI: https://blucreative.ca
**/


function acme_styles() 
{
    wp_enqueue_style( 'acme_css', plugins_url( '/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'slick_css', plugins_url( '/css/slick.css', __FILE__ ) );
    wp_enqueue_style( 'slick_theme_css', plugins_url( '/css/slick-theme.css', __FILE__ ) );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'slick_js', plugins_url( '/js/slick.min.js', __FILE__ ), '', '', true );
    wp_enqueue_script( 'datepicker_js', plugins_url( '/js/bootstrap-datepicker.min.js', __FILE__ ), '', '', true );
    wp_enqueue_script( 'acme_script', plugins_url( '/js/script.js', __FILE__ ), '', '', true );
}

add_action('wp_enqueue_scripts', 'acme_styles');



//Create admin settings page
add_action('admin_menu', 'acme_api_settings');
 
function acme_api_settings(){
        add_menu_page( 'ACME Settings', 'ACME Events API', 'manage_options', 'acme-settings', 'acme_options' );
        add_action( 'admin_init', 'register_acmesettings' );
}


//Whitelist our settings group, register individual inputs and settings
function register_acmesettings() {
    register_setting( 'acme_settings', 'acme_apikey' );
    register_setting( 'acme_settings', 'acme_env' );
    register_setting( 'acme_settings', 'acme_tenant_id' );
}


//ACME Admin options page display
function acme_options(){

    //register_setting( 'acme_events', 'acme_settings', 'optional_sanitize_callback' ); 
    settings_fields( 'acme_settings' );
    do_settings_sections( 'acme_settings' ); ?>

    <div class="wrap">
    <h1>ACME Events API - Settings</h1>
    <form class="form-table" method="post" action="options.php">
        <?php settings_fields('acme_settings') ?>
        <table>
            <tr>
                <td>
                    <label for="acme_apikey">
                       Enter your ACME API Key
                    </label>
                </td>
                <td>
                    <input type="text" name="acme_apikey" value="<?php echo esc_attr( get_option('acme_apikey') ); ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="acme_env">
                       ACME Environment <em>(sand1, prod, etc.)</em>
                    </label>
                </td>
                <td>
                    <input type="text" name="acme_env" value="<?php echo esc_attr( get_option('acme_env') ); ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="acme_tenant_id">
                       ACME Tenant ID <em>(integer)</em>
                    </label>
                </td>
                <td>
                    <input type="text" name="acme_tenant_id" value="<?php echo esc_attr( get_option('acme_tenant_id') ); ?>">
                </td>
            </tr>
            <?php do_settings_fields('acme_settings', 'default') ?>
        </table>
        <?php do_settings_sections('acme_settings') ?>
        <?php submit_button() ?>
    </form>
</div>

<?php } 


//Create our shortcode and atts
function acme_events_display($atts){

    //global $events, $tenantId, $env;

    //Set the shortcode attributes and defaults
    $a = shortcode_atts( array(
        'start' => date("Y-m-d"),
        'end' => '',
        'limit' => '10',
        'cal' => 0,
        'search' => '',
        'display' => 'slider',
        'sort' => 'asc',
        'filter' => '',
        'operator' => 'and',
        'audience' => '',
        'event' => '',
    ), $atts );

    $search = $a['search'];
    $strdate = $a['start'];
    $enddate = $a['end'];
    $sort = $a['sort'];
    $filter = $a['filter'];

    //Connect to Acme Events API
    require_once('acme_connect.php');

    //Debugging
    //echo $url1['url']; ?>

    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-<?php if ($a['cal'] == 1) { echo "8";} else {echo "12";} ?> ">
                <section class="carousel-wrapper cards">
                    <div id="<?php if ($a['display'] == "slider") { echo "myCarousel"; } ?>" class="row events-carousel">
                        <?php if (!empty($events)) { //Check if we have events to display
                            //Events loop
                            foreach(array_slice($events, 0, $a['limit']) as $event ) {
                                //loop variabls
                                $evdate = strstr($event["startTime"], 'T', true);
                                $evfulltime = trim(substr($event["startTime"], strpos($event['startTime'], 'T') +1));
                                $evtime = trim(substr($evfulltime, 0, strpos($evfulltime, ':') +3));
                                $eventlink = "https://" . $env . "-buy.acmeticketing.net/events/" . $tenantId . "/detail/" . $event['templateId'] . "?date=" . $event['startTime'];

                                //if filter=on, use CPT image, elseif no image uploaded use local default, else use ACME image
                                if ($filter == "on") {
                                    $eventimg = $event['customFields'][2]['value']; //Set to CPT array values
                                } elseif (empty($event['images'])){
                                    $eventimg = plugin_dir_url( __FILE__ ).'images/event-photo.png';
                                } else{
                                    foreach($event['images'] as $image) { //Find image marked as primary
                                        if ($image['primary'] == true){
                                            $eventimg = $image['screen'];
                                        break;
                                        }
                                        else{
                                            $eventimg = $image[0]['screen'];
                                        }
                                    }
                                }
                                echo '<div class="item carousel-card col-md-4 ' . ($a['display'] !="slider" ? "mb-5" : "") . '" id="' . $event['id'] . '">';
                                echo '<div class="h-100 card">';
                                echo '<a class="lead-img" target="_blank" rel="noopener" href="' . $eventlink . '"><img class="card-img-top img-fluid" src="' . $eventimg . '"></a>';
                                //echo '<div style="background:' . $event['colorCategory']['backgroundColor'] . ';">';
                                echo '<div class="card-body">';
                                echo '<a href="' . $eventlink . '" target="_blank" rel="noopener"><h6 class="card-title">' . $event['name'] . '</h6></a>';
                                //echo '<p class="card-text">' . $event['description'] . '</p>';
                                echo '<span class="details"><i class="fa fa-calendar" aria-hidden="true"></i> ' . $evdate . "</span>";
                                echo '<span class="details"><i class="fa fa-clock-o" aria-hidden="true"></i> ' . date('h:i A', strtotime($evtime)) . "</span>";
                                //echo '<span class="details"><i class="fa fa-ticket" aria-hidden="true"></i> $' . $event['priceList']['prices'][0]['price'] . '</span>';
                                echo '</div><div class="card-footer"><a class="btn btn-event" target="_blank" rel="noopener" href="' . $eventlink . '">Buy Tickets</a></div>';
                                echo '</div></div>';
                            }
                        }
                        else {
                            echo "<h4>No upcoming events</h4>";
                        } ?>
                    </div>
                </div>
            </div>
                    </section>
                    </div>
            <!-- Calendar options -->
            <?php if ($a['cal'] == 1) { 
                    echo '<div class="col-4">';
                    echo '<div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="input-sm form-control" name="start" />
                    <span class="input-group-addon">to</span>
                    <input type="text" class="input-sm form-control" name="end" />
                </div>';
                    echo '</div>';

                } ?>

<?php } ?>


<?php add_shortcode("acme_events", "acme_events_display");




/*To do:
1. templateId for live version (kids/adult and possibly others)
(maybe). Jquery calendar + ajax
*/

//start="2020-07-25"
//end="2021-07-25"
//limit="50"
//search="keyword"
//display="slider" / "grid"
//sort="asc" / "desc"
//filter="on"
//operator="and" / "or"
//audience="kids" / "adults" / "teens" / "pre-school"
//event="lecture" / "course" / "movie"

//Example shortcode [acme_events limit="10" search="NDG" start="2020-07-25" end="2021-07-25" display="grid" sort="desc"]

//Customer facing URL structure
//https://sand1-buy.acmeticketing.net/events/10002/detail/5e6802c860309f3308ad7b58
//https://sand1-buy.acmeticketing.net/events/[TENANT_ID]/detail/[EVENT_ID]]
?>