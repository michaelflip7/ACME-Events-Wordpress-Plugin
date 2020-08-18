<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sand1-api.acmeticketing.net/v2/b2b/event/instances?startTime=2020-04-19T14:00:00-04:00&endTime=2020-07-19T14:00:00-04:00",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "x-b2c-tenant-id: 10002",
    "x-acme-api-key: e67a2473baf540bea5eb9cd60d5dcd37",
    "Cookie: __cfduid=d2a79e580167012d7ff6b25c5bfa41ea51593537741"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$events = json_decode($response, true);
$events = $events['list'];


//Loop through our events return
foreach ($events as $event) {
    echo '<div style="background:' . $event['colorCategory']['backgroundColor'] . ';">';
    echo '<h3>' . $event['name'] . '</h1>';
    echo '<p>' . $event['id'] . '</p>';
    echo $event['description'] . '<br/>';
    echo $event['startTime'];
    echo '<h5>Price: $' . $event['priceList']['prices'][0]['price'] . '</h5>';
    echo '<a href="https://sand1-buy.acmeticketing.net/events/10002/detail/' . $event['id'] . '"><h2>View Event</h2></a>';
    echo '</div>';
}





/*To do:
1. Event images 
2. Pull variables from admin panel into return (TenantID, API Key, env)
3. shortcode setup 
4. shortcode variable implementation (# of events, )
5. Jquery calendar + ajax updating of date selection
*/

//Customer facing URL structure
//https://sand1-buy.acmeticketing.net/events/10002/detail/5e6802c860309f3308ad7b58
//https://sand1-buy.acmeticketing.net/events/[TENANT_ID]/detail/[EVENT_ID]]

?>