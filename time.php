<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<?php
$best_time = 990099;
$station = "ECM";

$lines = array("P", "D");

date_default_timezone_set("Europe/London");
foreach($lines as $line){
    $url = 'http://cloud.tfl.gov.uk/TrackerNet/PredictionDetailed/'.$line.'/'.$station;
    $xml_str = file_get_contents($url);
    try {
        $xmml = new SimpleXMLElement($xml_str);
        $platforms = $xmml -> S[0] -> P;
        $platforms_rev = array_reverse((array)$platforms);
        foreach(array(1) as $platformno) {
          $platform = $platforms[$platformno];
          foreach($platform -> T as $train) {
              $lastseen = time() - strtotime($train['DepartTime']);
              $timetoestimate = $train['SecondsTo'] - $lastseen;
              if ($timetoestimate > 0)
                $best_time = min($best_time, $timetoestimate);
          }
        }
    } catch (Exception $e) {
        //echo $e, $line;
    }

}

echo $best_time;
?>
