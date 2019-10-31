<?php 

require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;

// This creates the Reader object, which should be reused across
// lookups.
$reader = new Reader(dirname(__FILE__).'/GeoLite2-City.mmdb');

$ip = null;
if (!$ip && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
if (!$ip && isset($_SERVER['REMOTE_ADDR'])) {
  $ip = $_SERVER['REMOTE_ADDR'];
}
if (!$ip && isset($_REQUEST['ip'])) {
  $ip = $_REQUEST['ip'];
}
if (!$ip && isset($argv[1])) {
  $ip = $argv[1];
}

// Replace "city" with the appropriate method for your database, e.g.,
// "country".

try {
  $record = $ip ? $reader->city($ip) : null;
  // $record = $reader->city('46.149.80.233');
  if ($record) {


    print("IP: " .$ip . "\n"); // 'US'
    print("Country code: " .$record->country->isoCode . "\n"); // 'US'
    print("Country: " .$record->country->name . "\n"); // 'United States'
    // print($record->country->names['zh-CN'] . "\n"); // '美国'

    print("Subdivision: " .$record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
    print("Subdivision Code: " .$record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

    print("City: " .$record->city->name . "\n"); // 'Minneapolis'

    print($record->postal->code . "\n"); // '55455'

    print($record->location->latitude . "\n"); // 44.9733
    print($record->location->longitude . "\n"); // -93.2323

    print($record->traits->network . "\n"); // '128.101.101.101/32'
  } else {
    print("Private or invalid IP. Pass IP as query parameter ?ip=<IP>"); 
  }

} catch (Exception $ex) {
  print("Failure: ".$ex->getMessage()); 
}