<!DOCTYPE html>
<html lang="en">
<?php
$page = $_SERVER['PHP_SELF'];
$sec = "30";
?>
    <head>
        <link href='http://fonts.googleapis.com/css?family=VT323' rel='stylesheet' type='text/css'>
        <title>Tube Times</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
        <!-- Bootstrap -->
        <!--<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">-->
	<script type="text/javascript">
          function timedMsg()
          {
            var t=setInterval("change_time();",1000);
          }
          function pad (str, max)
          {
            return str.length < max ? pad("0" + str, max) : str;
          }
          function change_time()
          {
            var d = new Date();
            var curr_hour = pad(d.getHours(),2);
            var curr_min  = pad(d.getMinutes(),2);
            var curr_sec  = pad(d.getSeconds(),2);
            
            
            document.getElementById('Hour').innerHTML  = curr_hour+':';
            document.getElementById('Minut').innerHTML = curr_min+':';
      	    document.getElementById('Second').innerHTML= curr_sec;
          }
timedMsg();   
</script>
    </head>

<body style="text-align:center;">


<?php
//echo $_SERVER['QUERY_STRING'];


$station = "ECM";
$line = $_GET['l'];

//$lines = array("B", "C", "D", "H", "J", "M", "N", "P", "V", "W");
$lines = array("D");

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
<h1>
<?php
echo 'Trains from Ealing Common'
?>
</h1>
<center>
<h2>
<table>
 <tr>
  <td id="Hour" style="color:green;font-size:xx-large;"></td>
  <td id="Minut" style="color:green;font-size:xx-large;"></td>
  <td id="Second" style="color:red;font-size:xx-large;"></td>
  <tr>
</table>
</h2>
<div class="row">
<div class="span4">
<br />
<table class=\"table table-condensed\" style=\"width:100%; font-family: 'VT323', ; \">
<?php
foreach($lines as $line){
    $url = 'http://cloud.tfl.gov.uk/TrackerNet/PredictionDetailed/'.$line.'/'.$station;
    $xml_str = file_get_contents($url);
    try {
        $xmml = new SimpleXMLElement($xml_str);
        
        foreach($xmml -> S[0] -> P as $platform) {
            echo "<tr><td></td><td><h3 style=\"color:".$line_colours[$line]."\">", $platform[N], "</h3><br /></td></tr>";
            //echo "<table class=\"table table-condensed\" style=\"width:100%; \">";
            foreach($platform -> T as $train) {
	
                echo "<tr><td style='20%'><strong>", $train[Destination], "</strong></td> <td style='10%'>", $train[TimeTo], "</td> <td><small>", $train[Location], "</small></td></tr>";
            }
            //echo "</table>";
            }
            
    } catch (Exception $e) {
        //echo $e, $line;
    }

}

?>
</table>

</center>
</div>
</div>
    <!--<script src="http://code.jquery.com/jquery.js"></script>-->
    <!--<script src="../js/bootstrap.min.js"></script>-->
</body>

</html>
