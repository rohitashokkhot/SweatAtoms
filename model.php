<!DOCTYPE html>
<html>
<head>
	<script src="lightgl.js"></script>
  	<script src="csg.js"></script>
  	<script src="openjscad.js"></script>
	<style>
	body 
	{
  		font: 14px/20px 'Helvetica Neue Light', HelveticaNeue-Light, 'Helvetica Neue', Helvetica, Arial, sans-serif;
  	  	max-width: 820px;
  	  	margin: 0 auto;
  	  	padding: 10px;
	}
	canvas 
	{ 
		cursor: move; 
	}
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
		
		function updateSolid()
		{	
			var code1= document.getElementById('code1').value;
			var e = document.getElementById("menu");
			var choice = e.options[e.selectedIndex].value;
    		$('#timeval').load('fetch.php?choice='+ choice, function(response, status, xhr)
			{
				else if(choice==0)
				{
					var beats= document.getElementById('beats-graph').innerHTML;
					var chunk0="var hr=" + beats + ";";
				}
				else if (choice==1)
				{
					document.getElementById('result').innerHTML = "<a href='frog.stl'>Download STL </a>";
				}
				else if(choice==2)
				{
					var beats= document.getElementById('beats-flower').innerHTML;
					var chunk0="var hr=" + beats + ";";
				}
				else if(choice==3)
				{
					var beats= document.getElementById('beats-dice').innerHTML;
					var chunk0="var zone=" + beats + ";";
				}
				else if(choice==4)
				{
					var beats= document.getElementById('beats-bubbles').innerHTML;
					var chunk0="var br=" + beats + ";";
				}
	    		var code0 = "var schoice=" + choice + ";";
				var data = code0 + code1;
				var data = chunk0+code0 + code1;
				//alert(data);
				if(choice!=1)
					gProcessor.setJsCad(data);
			}); 
		}
</script>
<title>SweatAtoms</title>  
</head>

<body onload="onload()">
  <h1>SweatAtoms</h1>
	<div id="viewer"></div>
	<select id="menu">
	  <option value="0">Graph</option>
	  <option value="1">Frog</option>
	  <option value="2">Flower</option>
	  <option value="3">Dice</option>
	  <option value="4">Bubbles</option>
	</select>
	<button type="update" id="update" onclick="updateSolid()">Update!</button>
  <div id="timeval" style="display:none;"></div>
  <div id = "hbeats"> </div>
<textarea id="code1" style="display:none;">
	var cylresolution=16;
    function main()
	{
		if(schoice==0)
			var csg = graph();	
		else if(schoice==2) 
			var csg = flower();
		else if(schoice==3) 
			var csg = dice();
		else if(schoice==4) 
			var csg = bubbles();
		return csg;
	} 	  
	
	//----------------- GRAPH--------------
	function graph()
	{
		var shed = new CSG.Path2D(hr,false);
		var csg = shed.rectangularExtrude(2, 4, 20, true);
		return csg;
	}
	//----------------- GRAPH--------------
	
	
	//----------------- FLOWER --------------
	var cylresolution = 20;
	function main()
	{
	  	var result=new CSG();
	  	var length=hr.length; // spider has twice the number of teeth 
	  	var ringangle=0;
  	  	var max= 0;
	  	for(var i=0; i < length; i++)
	  	{
			if(max < hr[i])
			{
				max = hr[i];
			}
		}
		
		var result=CSG.cylinder({start:[0,0,0], end:[0,0,3], radius:4, resolution:20});
		var pies = new CSG();
	  	for(var i=0; i < length; i++)
	  	{
	    	var angle=i*360/length;
	    	var pie=makePie(hr[i],3,angle-90/length, angle+90/length); 
	    	pie=pie.translate([0,0,0]);
	    	pies=pies.union(pie);
	  	}
	 	//var hole = CSG.cylinder({start: [0,0,0], end: [0,0,3], radius:2, resolution:20});
	  	result = result.union(pies);
	  	var x = Math.cos(ringangle) * hr[0];
	  	var y = Math.sin(ringangle) * hr[0];

	  	var outercylinder=CSG.cylinder({start:[0,0,0], end:[0,0,2], radius:3, resolution:20});
		var outerhole = CSG.cylinder({start: [0,0,0], end: [0,0,2], radius:2, resolution:20});
		outercylinder=outercylinder.subtract(outerhole);
		outercylinder= outercylinder.rotateX(90);
		outercylinder=outercylinder.translate([x,y+1,2]);
		result=result.union(outercylinder);
	  	


		return result;
	}

	function makePie(radius, height, startangle, endangle)
	{
		var absangle=Math.abs(startangle-endangle);
	  	if(absangle >= 180)
	  	{
	    	throw new Error("Pie angle must be less than 180 degrees");
	  	}
	  	var numsteps=24*absangle/360;
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
	
	//----------------- FLOWER --------------
	
	//----------------- DICE--------------  
	function dice()
	{
		var cube1 = CSG.roundedCube({center: [0,0,0], radius: [10,10,10], roundradius: 2, resolution: 16});
		var m=1;
		//var radius = 1.5;
		var k = 0;
		for (var i=0; i<2;i++)
		{
			var start = m* 7;
			var end = m * 10;
			var cylinder = CSG.cylinder(
			{
			  start: [start, 0, 0],
			  end: [end, 0, 0],
			  radius: zone[k],
			  resolution: 16        // optional
			});
			k++;
			cube1 = cube1.subtract(cylinder);
			cylinder = CSG.cylinder(
			{
			    start: [0,start, 0],
			    end: [0, end, 0],
			    radius: zone[k],
			    resolution: 16        // optional
			});
			k++;
			cube1 = cube1.subtract(cylinder);	
			cylinder = CSG.cylinder(
			{
			   	start: [0, 0, start],
			    end: [0, 0, end],
			    radius: zone[k],
			    resolution: 16        // optional
			});
			k++;
			cube1 = cube1.subtract(cylinder);
			m=-1;
		}
		var bars = putbars(6);
		cube1 = cube1.subtract(bars);
		//cube1 = cube1.union(bars);
	  return cube1;
	}

	function putbars(length)
	{
		radius =7;
		var bars = new CSG();
		var m=1;
		var size =1;
		for(j=0;j<1;j++)
		{
			    var angle=j*360/1;
			    var x = Math.cos(angle * Math.PI/180) * radius;
			    var y = Math.sin(angle * Math.PI/180) * radius;
				x = m * x;
				y = m * y;
				z1 = m *7;
				z2 = m * 11;
			    var bar=CSG.cylinder({start:[x,z1,y], end:[x,z2,y], radius:size, resolution:16});
				bars = bars.union(bar);		    
		}
		for(j=0;j<2;j++)
		{
			    var angle=j*360/2;
			    var x = Math.cos(angle * Math.PI/180) * radius;
			    var y = Math.sin(angle * Math.PI/180) * radius;
				x = m * x;
				y = m * y;
				z1 = m *7;
				z2 = m * 11;
			    var bar=CSG.cylinder({start:[x,y,z1], end:[x,y,z2], radius:size, resolution:16});
				bars = bars.union(bar);		    
		}
		m=-1;
		for(j=0;j<3;j++)
		{
			    var angle=j*360/3;
			    var x = Math.cos(angle * Math.PI/180) * radius;
			    var y = Math.sin(angle * Math.PI/180) * radius;
				x = m * x;
				y = m * y;
				z1 = m *7;
				z2 = m * 11;
			    var bar=CSG.cylinder({start:[x,y,z1], end:[x,y,z2], radius:size, resolution:16});
				bars = bars.union(bar);		    
		}
		for(j=0;j<4;j++)
		{
			    var angle=j*360/4;
			    var x = Math.cos(angle * Math.PI/180) * radius;
			    var y = Math.sin(angle * Math.PI/180) * radius;
				x = m * x;
				y = m * y;
				z1 = m *7;
				z2 = m * 11;
			    var bar=CSG.cylinder({start:[x,z1,y], end:[x,z2,y], radius:size, resolution:16});
				bars = bars.union(bar);		    
		}
		for(j=0;j<5;j++)
		{
			    var angle=j*360/5;
			    var x = Math.cos(angle * Math.PI/180) * radius;
			    var y = Math.sin(angle * Math.PI/180) * radius;
				x = m * x;
				y = m * y;
				z1 = m *7;
				z2 = m * 11;
				var bar=CSG.cylinder({start:[z1,x,y], end:[z2,x,y], radius:size, resolution:16});
				bars = bars.union(bar);		    
		}
		return bars;
	}
	//----------------- DICE --------------
	
	//----------------- BUBBLES --------------
	function bubbles()
	{
		var resolution = 24; // increase to get smoother corners (will get slow!)
	    var result=new CSG();
	    var cyl3=new CSG();
	    var cyl=CSG.cylinder({start:[0,0,0], end:[0,0,3], radius:11, resolution:resolution});
		var centerhole = CSG.cylinder({start: [0,0,0], end: [0,0,3], radius: 10, resolution:resolution});
		//cyl = cyl.subtract(centerhole);
	    var length =12;
	    length2 = br.length;
		for (i=0; i<length2; i++)
		{
	        var angle=i*360/length;
	        var x = Math.cos(angle * Math.PI/180) * 11;
	        var y = Math.sin(angle * Math.PI/180) * 11;
		    random2=br[i];
		    if(br[i]!=0)
		    {
				var cyl2=CSG.cylinder({start:[x,y,0], end:[x,y, 3], radius:br[i], resolution:resolution});
				var hole2 = CSG.cylinder({start:[x,y,0], end:[x,y, 3], radius:br[i]-1, resolution:resolution});
			  	cyl2 = cyl2.subtract(hole2);
	        	cyl3 = cyl3.union(cyl2);
			}
		}
		cyl3 = cyl3.union(cyl);
		cyl3 = cyl3.subtract(centerhole);
		return cyl3;
	}
	//----------------- BUBBLES --------------	
</textarea>
<div id="result"> </div>
</body>
</html>