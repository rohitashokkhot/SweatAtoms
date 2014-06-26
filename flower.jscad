
	var hr=[11,13,10,13,10,13,15,19,12,15,11,16,13,16,19,16,12,8,12,7,12,15,11];
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
		
		var result=CSG.cylinder({start:[0,0,0], end:[0,0,3], radius:4, resolution:cylresolution});
		var pies = new CSG();
	  	for(var i=0; i < length; i++)
	  	{
	    	var angle=i*360/length;
	    	var pie=makePie(hr[i],3,angle-90/length, angle+90/length); 
	    	pie=pie.translate([0,0,0]);
	    	pies=pies.union(pie);
	  	}
	 	//var hole = CSG.cylinder({start: [0,0,0], end: [0,0,3], radius:2, resolution:cylresolution});
	  	result = result.union(pies);
	  	var x = Math.cos(ringangle) * hr[0];
	  	var y = Math.sin(ringangle) * hr[0];

	  	var outercylinder=CSG.cylinder({start:[0,0,0], end:[0,0,2], radius:3, resolution:cylresolution});
		var outerhole = CSG.cylinder({start: [0,0,0], end: [0,0,2], radius:2, resolution:cylresolution});
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