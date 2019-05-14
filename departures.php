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

$eb_map = array("TPAECM" => 1000,
                "TPAABECM" => 900,
                "TPBBECM" => 800,
                "TPCECM" => 700,
                "TPDECM" => 600,
                "TPFECM" => 500
                );

$wb_map = array("558",
                "O",
                "P");



?>
<center>
<br />
<table class="table table-condensed" style="width:100%; font-family: 'VT323'; color:#FFBF00; font-size: 30px; font-weight: 400; ">
<?php
date_default_timezone_set("Etc/GMT");


$wbdepartures = array();
$ebdepartures = array();

foreach($lines as $line){
    $url = 'http://cloud.tfl.gov.uk/TrackerNet/PredictionDetailed/'.$line.'/'.$station;
    $xml_str = file_get_contents($url);
    $logentry = time().",";
    try {
        $xmml = new SimpleXMLElement($xml_str);
        $platforms = $xmml -> S[0] -> P;
        $platforms_rev = array_reverse((array)$platforms);
        foreach(array(1,0) as $platformno) {
          $platform = $platforms[$platformno];
          foreach($platform -> T as $train) {
              if($platformno == 0){
                    array_push($wbdepartures, $train);
              } else {
                    array_push($ebdepartures, $train);
                     }
          }
        }
    } catch (Exception $e) {
        //echo $e, $line;
    }

}

//Sort


function TimeSort($a, $b) {
    return strnatcmp($a["TimeTo"], $b["TimeTo"]);
};

usort($ebdepartures, "TimeSort");
usort($wbdepartures, "TimeSort");

//Display
echo "<tr><td id=\"platform\">", "Platform 1 - Eastbound", "<br /></td></tr>";
//echo "<tr><td style='width: 0%'><img src=\"img/eb-track.jpg\" /></td></tr>";

//echo "<tr><td><img STYLE=\"position:absolute; LEFT:150px;\"  src=\"img/DistTrainEB.jpg\" /></td></tr>";
//<img src=\"img/PiccTrainEB.jpg\" />

$traincounter = 0;
foreach($ebdepartures as $train) {
    $lastseen = time() - strtotime($train['DepartTime']);
    $timetoestimate = $train['SecondsTo'] - $lastseen;
    if ($traincounter<5)
        echo "<tr><td id=\"departtime\"><strong>", $train['Destination'], "</strong></td> <td><div class='time'>",$lastseen>120?("Stuck ".$train['TimeTo']." away"):$timetoestimate,  "</div></td> <td style='width: 0%'>","</td></tr>";
    $traincounter++;
    }
    




echo "<tr><td id=\"platform\">", "Platform 2 - Westbound", "<br /></td></tr>";
$traincounter = 0;
foreach($wbdepartures as $train) {
    $lastseen = time() - strtotime($train['DepartTime']);
    $timetoestimate = $train['SecondsTo'] - $lastseen;
    if ($traincounter<5)
        echo "<tr><td id=\"departtime\"><strong>", $train['Destination'], "</strong></td> <td><div class='time'>",$lastseen>120?("Stuck ".$train['TimeTo']." away"):$timetoestimate,  "</div></td> <td style='width: 0%'>","</td></tr>";
    $traincounter++;
    }

?>
</table>
</center>
Stuck trains haven't seen movement for 2 minutes.
