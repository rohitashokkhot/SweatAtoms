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


<script>
$(document).ready(function()
{
    //var xml = $.parseXML("rohitakhot_18.07.2013_export.xml"),
    //$xml = $( xml ),
    //$test = $xml.find('values');
	//console.log($test.text());
	
});
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
	   //$('#timeval').load('index9.php?token=<?php echo $token; ?>', function(response, status, xhr)
		 //  {
	    		var newhr= $("#hr").html();
				//alert(newhr);
				hr = newhr.split(',');
				//$.plot($("#placeholder"), [{ label: "Heart rate", data: hr }, ], { yaxis: { max: 150 } });
				//hr.push(newhr - 30);
				//hr.push(140);
				var firstparam= "var hr= ["; 
				//hr[0]=Math.ceil((hr[0])/10)*10;
				hbeats = hbeats + "[0," + hr[0] + "]";
				var b=0;
				var avg=0;
				var secondparam="var avghr=[";
				alert(secondparam);
     			firstparam = firstparam + "[0," + hr[0] + "]";
				for (i = 1; i < hr.length; i+=60) 
				{
					//hr[i]=Math.ceil((hr[i])/10)*10;
					//hr[i]=Math.ceil((hr[i]+1)/10)*10;
					firstparam = firstparam + "," + "[" + b + "," + hr[i] + "]";
					hbeats = hbeats + "," + "[" + b + "," + hr[i] + "]";
					b++;
				}
				for (i = 1; i < hr.length; i+=3600) 
				{
					avg=0;
					for (j = i; j < (i+3600); j+=60)
					{
						avg = (int) avg + hr[j];
						//alert("hr:" + hr[j]);
					}
					alert(avg);
					if(i==1)
					{
					secondparam = secondparam + avg;
					}
					else
					{
						secondparam = secondparam + "," + avg;
					}
				}
				secondparam = secondparam + "]";
				//alert(secondparam);
				//max= 120;
				//firstparam = firstparam + max + "];";
				firstparam = firstparam + "];";
				//alert(firstparam);
				$("#hbeats").html(hbeats);
				//var secondparam="var numteeth=" + count + ";";
				var code1 = $('#code1').val();
	    		//var code2 = $('#code2').val();
	    		var data = firstparam +  code1;
	    		//alert(data);
				
	    		gProcessor.setJsCad(data); 
			
		 //  });
}
</script>
<title>SweatAtoms</title>  
</head>

<body onload="onload()">
  <h1>SweatAtoms</h1>
	<div id="viewer"></div>
	<div id="hr">
	<?php

	$xml=simplexml_load_file("robert-19jul-7hrs.xml");
	$string= (string)$xml->{"calendar-items"}->exercise->result->samples->sample->values;
	echo $string;

	?>
</div>
  <div id="timeval" style="display:none;"></div>
  <div id = "hbeats"> </div>
<textarea id="code1" style="display:none;">
	var cylresolution=16;
    
	

	function main()
	{	 
		//var csg = ring();
		//var csg = plotgraph();
		//return csg;
		//var newhr= $("#hr").html();
		//alert(newhr);
	} 	  
	
	function plotgraph()
	{
		//var vec = new CSG.path2D(points);
		var shed = new CSG.Path2D(hr,false);
		var csg = shed.rectangularExtrude(2, 8, 16, true);
		return csg;
	}
	function ring ()	
	{
	  var resolution = 24; // increase to get smoother corners (will get slow!)
		   var result=new CSG();
		 var bars=new CSG();
		   var cyl=CSG.cylinder({start:[0,0,0], end:[0,0,2], radius:14, resolution:resolution});
	           var cyl2=CSG.cylinder({start:[0,0,6], end:[0,0,8], radius:14, resolution:resolution});
		   cyl = cyl.union(cyl2);
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
		cyl = cyl.union(bars);
		result = cyl;
		  return result;
		}
</textarea>
</body>
</html>