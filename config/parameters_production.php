<?php
$services = getenv("VCAP_SERVICES");
$services_json = json_decode($services, true);
$parameters = array(
        'mongodb' => $services_json["mongodb-1.8"][0]["credentials"]
    );

return $parameters;