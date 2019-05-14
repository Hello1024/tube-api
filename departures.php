<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<?php
//echo $_SERVER['QUERY_STRING'];


$station = "ECM";
// $line = $_GET['l'];

//$lines = array("B", "C", "D", "H", "J", "M", "N", "P", "V", "W");
$lines = array("P", "D");

$line_colours = array(//"B" => "#AB4800",
                      //"C" => "#FF0D00",
                      "D" => "#09BA00",
                      "H" => "#FFD600",
                      "J" => "#858C8C",
                      "M" => "#910099",
                      "N" => "#000000",
                      "P" => "#001DF2",
                      "V" => "#26CFFF",
                      "W" => "#5CADAD"
                      );


?>
<center>
<br />
<table class="table table-condensed" style="width:100%; font-family: 'VT323'; color:#FFBF00; font-size: 30px; font-weight: 400; ">
<?php
date_default_timezone_set("Europe/London");
foreach($lines as $line){
    $url = 'http://cloud.tfl.gov.uk/TrackerNet/PredictionDetailed/'.$line.'/'.$station;
    $xml_str = file_get_contents($url);
    $file = "logfile.csv";
    $logentry = time().",";
    try {
        $xmml = new SimpleXMLElement($xml_str);
        $platforms = $xmml -> S[0] -> P;
        $platforms_rev = array_reverse((array)$platforms);
        foreach(array(1,0) as $platformno) {
          $platform = $platforms[$platformno];
          echo "<tr><td id=\"platform\">", $platform['N'], "<br /></td></tr>";
          $traincounter = 0;
          foreach($platform -> T as $train) {
              $logentry .= strtotime($train['DepartTime']) . "," . $train['TrackCode'] . "," . $train['TripNo'] . $train['SetNo'] . $train['LCID'] . ",";
              $lastseen = time() - strtotime($train['DepartTime']);
              $timetoestimate = $train['SecondsTo'] - $lastseen;
              if ($traincounter<5)
                echo "<tr><td id=\"departtime\"><strong>", $train['Destination'], "</strong></td> <td><div class='time'>",$lastseen>120?("Stuck ".$train['TimeTo']." away"):$timetoestimate,  "</div></td> <td style='width: 0%'>","</td></tr>";
              $traincounter++;
          }
        }
        file_put_contents($file, $logentry . "\n", FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        //echo $e, $line;
    }

}

?>
</table>
</center>
Stuck trains haven't seen movement for 2 minutes.
