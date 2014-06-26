var currentCount=1;
	var x = new Array(maxCount);
	var y = new Array(maxCount);
	var r = new Array(maxCount);
	var maxCount=20;
	var width=100;
	var height=100;
	var closestIndex = new Array(maxCount);
	var minRad = 3;
	var maxRad = 10;
	
function dist(x1,y1,x2,y2)
{
	var sum = (x2-x1) * (x2-x1) + (y2-y1) * (y2-y1);
	var d = Math.sqrt(sum);
	return d;
}	
function main()
{
	var resolution= 16;

	

	//var closestIndex = array(maxCount);
	x[0]=50;
	y[0]=50;
 	r[0]=10;
	closestIndex[0]=0;
	var csg= new CSG();
	for(i=currentCount; i<=maxCount; i++)
	{
		var shape2 = draw();
		var result = shape2.extrude(
		{
			offset: [0, 0, 5],   // direction for extrusion
			twistangle: 0,       // top surface is rotated 30 degrees 
			twiststeps: 0        // create 10 slices
		});
		csg = csg.union(result);
	}
    var shape5 = CAG.roundedRectangle({center: [50, 50], radius: [60, 60], roundradius: 3, resolution: 16});  
	var result = shape5.extrude(
	{
		offset: [0, 0, 5],   // direction for extrusion
		twistangle: 0,       // top surface is rotated 30 degrees 
		twiststeps: 0        // create 10 slices
	});
	result = result.subtract(csg);  
	return result;
}

function draw()
{
	var csg= new CAG();
	//var newR = Math.floor((Math.random()*7)+1);
	var newX = Math.floor((Math.random()*(width-maxRad))+maxRad);
	var newY = Math.floor((Math.random()*(height-maxRad))+maxRad);
	var newR = minRad;
	
	var intersection = 0;
	
	for(i=0;i<currentCount;i++)
	{
		var d = dist(newX, newY, x[i],y[i]);
		if(d< (newR + r[i]))
		{
			intersection = 1;
			break;
		}
	}
	
	if(intersection==0)
	{
		var newRad = width;
		for(i=0;i<currentCount;i++)
		{
			var d = dist(newX, newY, x[i],y[i]);
			if(newRad > (d -r[i]))
			{
				newRad = d - r[i];
				closestIndex [currentCount] = i; 
			}
		}
		if(newRad > maxRad)
			newRad = maxRad;
		
		x[currentCount] = newX;
		y[currentCount] = newY;
		r[currentCount] = newRad;
		currentCount++;
	}
	
	for(i=0;i<currentCount;i++)
	{
		var shape2 = CAG.circle({center: [x[i], y[i]], radius: r[i], resolution: 16});
		csg = csg.union(shape2); 		
	}
	return csg;
}