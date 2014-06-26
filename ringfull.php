<!DOCTYPE html>

<html><head>
  <script src="lightgl.js"></script>
  <script src="csg.js"></script>
  <script src="openjscad.js"></script>

  <style>

body {
  font: 14px/20px 'Helvetica Neue Light', HelveticaNeue-Light, 'Helvetica Neue', Helvetica, Arial, sans-serif;
  max-width: 820px;
  margin: 0 auto;
  padding: 10px;
}
canvas { cursor: move; }

  </style>
<link rel="stylesheet" href="openjscad.css" type="text/css">


<script src="jquery-1.6.2.min.js"></script>

<?php
require('lib/sfYamlParser.php');
require('lib/runkeeperAPI.class.php');
$rkAPI = new runkeeperAPI('config/rk-api.sample.yml');
if ($rkAPI->api_created == false) {
	echo 'error '.$rkAPI->api_last_error; /* api creation problem */
	exit();
}
if ($_GET['code']) {
	$auth_code = $_GET['code'];
	if ($rkAPI->getRunkeeperToken($auth_code) == false) {
		echo $rkAPI->api_last_error; /* get access token problem */
		exit();
	}
	else {
		//echo "Access token:".$rkAPI->access_token;
		$token= $rkAPI->access_token;
	}
}
?>
<script>

var gProcessor=null;

// Show all exceptions to the user:
OpenJsCad.AlertUserOfUncaughtExceptions();

function onload()
{
  gProcessor = new OpenJsCad.Processor(document.getElementById("viewer"));
  updateSolid();
}

function updateSolid()
{
	var hr= [];
	var oldcount=0;
	var count=0;
	var hbeats= 'Heartbeats: ';
	   $('#timeval').load('index9.php?token=<?php echo $token; ?>', function(response, status, xhr)
		   {
	    		var newhr= $("#hr").html();
				hr = newhr.split(',');
				//$.plot($("#placeholder"), [{ label: "Heart rate", data: hr }, ], { yaxis: { max: 150 } });
				//hr.push(newhr - 30);
				//hr.push(140);
				var firstparam= "var hr= ["; 
				//hr[0]=Math.ceil((hr[0])/10)*10;
				hbeats = hbeats + hr[0] + ", ";
				hr[0]=hr[0]-30;
				var recorded = hr[0];
				var gap=10;
				firstparam = firstparam + hr[0]+ ",";
				
				var max = hr[0];
				for (i = 1; i < hr.length; i++) 
				{
					//hr[i]=Math.ceil((hr[i])/10)*10;
					hbeats = hbeats + hr[i] + ", ";
					hr[i]=hr[i]-30;
					if(hr[i]>max)
						max = hr[i];
					if((hr[i]>= recorded+gap)||(hr[i]<= recorded-gap))
					{ 
						recorded = hr[i];
						
						//hr[i]=Math.ceil((hr[i]+1)/10)*10;
						firstparam = firstparam + hr[i]+ ",";
						hbeats = hbeats + hr[i] + ", ";
					}
				}
				//max= 120;
				//firstparam = firstparam + max + "];";
				firstparam = firstparam + "];";
				//alert(firstparam);
				$("#hbeats").html(hbeats);
				var secondparam="var numteeth=" + count + ";";
				var code1 = $('#code1').val();
	    		var code2 = $('#code2').val();
	    		var data = code1 + firstparam + secondparam + code2;
	    		//alert(data);
				
	    		gProcessor.setJsCad(data); 
			
		   });
}
</script>
<title>SweatAtoms</title>  
</head>

<body onload="onload()">
  <h1>SweatAtoms</h1>
	<div id="viewer"></div>
  <div id="timeval" style="display:none;"></div>
  <div id = "hbeats"> </div>
<textarea id="code1" style="display:none;">
	var cylresolution=16;
    
	function getParameterDefinitions() {
	   return [
	      { name: 'name', initial: "Joe Example", type: 'text', caption: 'Your name', size: 30 },
	      { name: 'title', initial: "3D Printer Developer", type: 'text', caption: 'Your title', size: 30 },
	      { name: 'thickness', initial: 3, type: 'float', caption: 'Thickness' }
	   ];
	}
	

	function main()
	{	  
	  
</textarea>

	  
<textarea id="code2" style="display:none">
	  var resolution = 24; // increase to get smoother corners (will get slow!)
	   var result=new CSG();
	 var bars=new CSG();
	   var cyl=CSG.cylinder({start:[0,0,0], end:[0,0,10], radius:14, resolution:resolution});
	 
		  var centerhole = CSG.cylinder({start: [0,0,0], end: [0,0,10], radius: 10, resolution:resolution});
	var length=12; 
	for (i=0; i<length; i++)
	{
	    var angle=i*360/length;
	    var x = Math.cos(angle * Math.PI/180) * 14;
	    var y = Math.sin(angle * Math.PI/180) * 14;
	    var random2 = Math.floor((Math.random()*9)+1);
	    var bar=CSG.cylinder({start:[x,y,0], end:[x,y,random2], radius:2, resolution:resolution});
	    bars = bars.union(bar);
	}
		   cyl = cyl.subtract(centerhole);
	cyl = cyl.subtract(bars);
	result = cyl;
	  return result;
	}
</textarea>
</body>
</html>