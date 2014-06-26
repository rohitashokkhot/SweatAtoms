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
var gProcessor=null;

// Show all exceptions to the user:
OpenJsCad.AlertUserOfUncaughtExceptions();

function onload()
{
	gProcessor = new OpenJsCad.Processor(document.getElementById("viewer"));
	updateSolid();
}

function update()
{
	//gProcessor = new OpenJsCad.Processor(document.getElementById("viewer"));
	updateSolid();
	//alert("hi");
}

function updateSolid()
{
	var code1= document.getElementById('code1').value;
	var e = document.getElementById("menu");
	var choice = e.options[e.selectedIndex].value;
    var code0 = "var schoice=" + choice + ";";
	var data = code0 + code1;
	
    /*$('#timeval').load('parsexml.php', function(response, status, xhr)
	{
		
	});*/
	var data = code0 + code1;
	//var data=code1;
	alert(data);
	gProcessor.setJsCad(data); 
}
</script>
<title>SweatAtoms</title>  
</head>

<body onload="onload()">
  <h1>SweatAtoms</h1>
	<div id="viewer"></div>
	<select id="menu">
	  <option value="0">graph</option>
	  <option value="1">frog</option>
	  <option value="2">flower</option>
	  <option value="3">leaf</option>
	  <option value="4">ring</option>
	</select>
	<button type="update" id="update" onclick="update()">Update!</button>
  <div id="timeval" style="display:none;"></div>
  <div id = "hbeats"> </div>
<textarea id="code1" style="display:none;">
	var cylresolution=16;
    function main()
	{	
		if(schoice==4) 
			var csg = ring();
		else if(schoice==2) 
			var csg = flower();
		else 
			var csg = graph();
		return csg;
	} 	  
	
	function graph()
	{
		//var vec = new CSG.path2D(points);
		var shed = new CSG.Path2D([[0,10], [0,20]],false);
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
  	  function flower()
  	  {
	  
  	  var outerdiameter= 60;
  	  var spidercenterdiameter=outerdiameter/2;
  	  var spiderlength=25;
  	  var result=new CSG();
  	  var numspiderteeth=12; //hr.length; // spider has twice the number of teeth 
  	  var ringangle=0;
  	  for(var i=0; i < numspiderteeth; i++)
  	  {
  	    var angle=i*360/numspiderteeth;
  		//var random1 = Math.floor((Math.random()*25)+1);
  		var random2 = Math.floor((Math.random()*15)+1);
  		//var random2=0;
  	    var pie=makePie(random2, random2,angle-90/numspiderteeth, angle+90/numspiderteeth); 
  	    pie=pie.translate([0,0,0]);
  	    result=result.union(pie);
  	  }
	  
  	  var x = Math.cos(ringangle) * 90/2;
  	  var y = Math.sin(ringangle) * 90/2;

  	  	var outercylinder=CSG.cylinder({start:[0,0,0], end:[0,0,4], radius:spidercenterdiameter/4, resolution:cylresolution});
  	var outerhole = CSG.cylinder({start: [0,0,0], end: [0,0,4], radius: spidercenterdiameter/8, resolution:cylresolution});
  	outercylinder=outercylinder.subtract(outerhole);
  	outercylinder= outercylinder.rotateX(90);
  	outercylinder=outercylinder.translate([x,y+2,90/8]);

  	  	result=result.union(outercylinder);
	  

  
  	   //result = result.subtract(outerhole);
  	  var centercylinder=CSG.cylinder({start:[0,0,0], end:[0,0,spiderlength], radius:spidercenterdiameter/4, resolution:cylresolution});
  	  result=result.union(centercylinder);
  	  var centerhole = CSG.cylinder({start: [0,0,0], end: [0,0,spiderlength], radius: spidercenterdiameter/8, resolution:cylresolution});
  	   result = result.subtract(centerhole);

  	  return result;
  	}
	
  	function makePie(radius, height, startangle, endangle)
  	{
  	  var absangle=Math.abs(startangle-endangle);
  	  if(absangle >= 180)
  	  {
  	    throw new Error("Pie angle must be less than 180 degrees");
  	  }
  	  var numsteps=cylresolution*absangle/360;
  	  if(numsteps < 1) numsteps=1;
  	  var points=[];
  	  for(var i=0; i <= numsteps; i++)
  	  {
  	    var angle=startangle+i/numsteps*(endangle-startangle);
  		ringangle=angle;
  	    var vec = CSG.Vector2D.fromAngleDegrees(angle).times(radius);
  	    points.push(vec);    
  	  }
  	  points.push(new CSG.Vector2D(0,0));
  	  var shape2d=new CSG.Polygon2D(points);
  	  var extruded=shape2d.extrude({
  	    offset: [0,0,height],   // direction for extrusion
  	  });

  	  return extruded;  
  	}
</textarea>
</body>
</html>