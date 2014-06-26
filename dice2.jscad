//var radius = 6;
function main()
{
  var csg = dice();
  return csg;
}
var zone = [5.5,1.5,1,1,1,1];

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
		    var bar=CSG.cylinder({start:[z1,x,y], end:[z2,x,y], radius:size, resolution:16});
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
		    var bar=CSG.cylinder({start:[x,z1,y], end:[x,z2,y], radius:size, resolution:16});
			bars = bars.union(bar);		    
	}
	return bars;
}

