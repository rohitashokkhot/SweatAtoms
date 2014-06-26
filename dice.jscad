function main()
{
	var cube1 = CSG.roundedCube({center: [0,0,0], radius: [10,10,10], roundradius: 2, resolution: 16});
	var m=1;
	for (var i=0; i<2;i++)
	{
		var start = m* 7;
		var end = m * 10;
		var cylinder = CSG.cylinder(
		{
		  start: [start, 0, 0],
		  end: [end, 0, 0],
		  radius: 5,
		  resolution: 16        // optional
		});
		cube1 = cube1.subtract(cylinder);
		cylinder = CSG.cylinder(
		{
		    start: [0,start, 0],
		    end: [0, end, 0],
		    radius: 5,
		    resolution: 16        // optional
		});
		cube1 = cube1.subtract(cylinder);	
		cylinder = CSG.cylinder(
		{
		   	start: [0, 0, start],
		    end: [0, 0, end],
		    radius: 5,
		    resolution: 16        // optional
		});
		cube1 = cube1.subtract(cylinder);
		m=-1;
	}
	var bars = putbars(0,0,6,0,0,10,5);
	cube1 = cube1.union (bars);
  return cube1;
}
function putbars(x1,y1,z1,x2,y2,z2,length)
{
	var radius = 10;
	var bars = new CSG();
	for (i=0; i<length; i++)
	{
	    var angle=i*360/length;
	    var x = Math.cos(angle * Math.PI/180) * radius;
	    var y = Math.sin(angle * Math.PI/180) * radius;
	    //var random2 = Math.floor((Math.random()*25)+1);
	    var bar=CSG.cylinder({start:[x1,y1,z1], end:[x2,y2,z2], radius:4, resolution:resolution});
	    bars = bars.union(bar);
	}
	return bars;
}