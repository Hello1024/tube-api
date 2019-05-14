<!DOCTYPE html>
<html lang="en">
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta content="utf-8" http-equiv="encoding">
<!--        <link href='http://fonts.googleapis.com/css?family=VT323' rel='stylesheet' type='text/css'> -->
        <title>Tube Times</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <!--<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        
	<script type="text/javascript">
window.onerror = function(message, source, lineno, colno, error) { 

   $( "#departs" ).html( "<div style='color:#ffc;font-size:xx-large;'>I'm broken! " + message + source + lineno + colno + error + 
"</div>" );

}
	
	function load() {
    // refresh iframe
  //  $( "#tubeStatus" ).attr( 'src', function ( i, val ) { return val; });
    
    // refresh departure times
    $.ajax({
      url: "departures.php",
    }).done(function( html ) {
      try {
        $( "#departs" ).html( html );
        //retime();
      } catch (e) {
        $( "#departs" ).html( "<div style='color:#ffc;font-size:xx-large;'>I'm broken! " + e + "</div>" );
      }
    }).error(function( xhr, e1, e2 ) {
      $( "#departs" ).html( "<div style='color:#ffc;font-size:xx-large;'>I'm broken! " + e1 + " " + e2 + "</div>" );
    });
  };
  
  function retime() {
    // reduce all timers
    $( ".time" ).each(function(i, el) {
      var txt = el.innerHTML;
      if (txt != "Arrived") {
        var t_int;
        if (txt.indexOf(":")!=-1) {
          var a = txt.split(':'); // split it at the colons

          t_int = (+a[0]) * 60 + (+a[1]); 
        } else {
          t_int = (+txt); 
        }
        t_int-=1;
        if (isNaN(t_int)) return; 
        if (t_int<=0) {
          $(el).text("Arrived");
        } else {
          $(el).text(Math.floor(t_int/60) + ":" + ("00" + (t_int%60)).substr(-2,2));
        }        
      }
    
    });
  
  }
    
  $(document).ready(function(){
	  setInterval(load, 20000);
	  setInterval(retime, 1000);
	  load();
  });

</script>
    </head>

<body>

<center>
<!-- <iframe id="tubeStatus" src="http://l/www.tfl.gov.uk/tfl/syndication/feeds/serviceboard-fullscreen.htm" 
style="width:25%;height:780px;float: right;margin:0;padding:0;"></iframe> -->
<img src="img/ecm.jpg" />

<div id="departs" style="width:70%;
        margin:0;padding:0;float: left"></div>



</body>

</html>
