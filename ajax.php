<?php
if(isset($_GET['url']) && preg_match('/^http(s)?:/', $_GET['url'])){
    $api =  file_get_contents("ajax.php?url=http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&flat=36.5720&flon=140.6432&tlat=36.3582232&tlon=140.4773157&v=bicycle&fast=1&layer=mapnik");
    header('Content-type: application/json');
    $geojson = json_encode($api);
    echo $geojson;
}
?>