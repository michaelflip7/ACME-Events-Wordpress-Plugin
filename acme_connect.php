<?php 



//Load our ACME Events via API and format it
$curl = curl_init();

//Fetch admin panel settings
$apikey = get_option('acme_apikey');
$tenantId = get_option('acme_tenant_id');
$env = get_option('acme_env');

//$strdate = "2020-04-19";
//$enddate = "2020-07-01";
//$strdate = date("Y-m-d");

//Set our CURL connection to the standard GET call, or POST call (for event filtering)
if($filter == "") {
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://$env-api.acmeticketing.net/v2/b2b/event/instances?startTime=" . $strdate . "T00:00:00&" . ($enddate !="" ? "endTime=" . $enddate . "T23:00:00&" : "") . "search=$search",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "x-b2c-tenant-id: $tenantId",
      "x-acme-api-key: $apikey",
      "Cookie: __cfduid=d2a79e580167012d7ff6b25c5bfa41ea51593537741"
    ),
  ));
}
elseif($filter == "on") {

  $data_post = '
  {
    "startTime" : "' . $strdate . 'T00:00:00",
    "endTime" : "' . $enddate . 'T23:00:00",
    "pagination": {
        "sortDirection": "' . $sort . '",
        "sortField": "startDate"
    },
    "customFields": {
        "operator": "' . $a['operator'] . '",
        "fields": [
            {
                "name": "customField2",
                "values": ["' . $a['audience'] . '"]
            },
            {
                "name": "customField1",
                "values": ["' . $a['event'] . '"]
            }
        ]
    }
}';

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://$env-api.acmeticketing.net/v2/b2b/event/instances/summaries",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "$data_post",
    CURLOPT_HTTPHEADER => array(
      "x-b2c-tenant-id: $tenantId",
      "x-acme-api-key: $apikey",
      "Content-Type: application/json",
      "Cookie: __cfduid=d2a79e580167012d7ff6b25c5bfa41ea51593537741"
    ),
  ));
}

//Connect
$response = curl_exec($curl);

$url1 = curl_getinfo($curl);

curl_close($curl);

$events = json_decode($response, true);

if (isset($events['list'])) {
  $events = $events['list'];

  if($sort == "asc") {
    //Sort our events $a -> $b (today -> tomorrow)
    usort($events, function ($a, $b) {
      return strtotime($a['startTime']) - strtotime($b['startTime']);
    });
  }
  else {
    //Sort our events $b -> $a (tomorrow -> today)
    usort($events, function ($a, $b) {
      return strtotime($b['startTime']) - strtotime($a['startTime']);
    });
  }
}
else {
  $events = NULL;
}
?>