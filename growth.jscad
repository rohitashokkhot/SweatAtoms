var currentCount=1;
	var x = new Array(maxCount);
	var y = new Array(maxCount);
	var r = new Array(maxCount);
	var maxCount=10;
	var width=500;
	var height=600;
	
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
	x[0]=width/2;
	y[0]=height/2;
 	r[0]=10;
	var csg= new CSG();
	for(i=currentCount; i<maxCount; i++)
	{
		var csg2 = draw();
		csg = csg.union(csg2);
	}
	return csg;
}

function draw()
{
	var csg= new CSG();
        var width=500;
	var height=600;
	var newR = Math.floor((Math.random()*7)+1);
	var newX = Math.floor((Math.random()*(width-newR))+newR);
	var newY = Math.floor((Math.random()*(height-newR))+newR);
	
	var closestDist = 100000000;
	var closestIndex = 0;
	
	for(i=0; i<currentCount; i++)
	{
		newDist = dist(newX, newY, x[i],y[i]);
		if(newDist < closestDist)
		{
			closestDist = newDist;
			closestIndex = i;
		}
	}
	
	var angle= Math.atan2(newY - y[closestIndex], newX-x[closestIndex]);
	
	x[currentCount]= x[closestIndex] + Math.cos(angle) * (r[closestIndex + newR]);
	y[currentCount]= y[closestIndex] + Math.sin(angle) * (r[closestIndex + newR]);
	r[currentCount]= newR;
	currentCount++;
	
	for (i=0;i<currentCount; i++)
	{
		//var shape2 = CAG.circle({center: [x[i], y[i]], radius: r[i], resolution: 20});
		//var result = shape2.extrude({
		  offset: [0, 0, 10],   // direction for extrusion
		  twistangle: 0,       // top surface is rotated 30 degrees 
		  twiststeps: 10        // create 10 slices
		});
		
		var sphere = CSG.sphere({
		  center: [x[i], y[i], 0],
		  radius: r[i],            // must be scalar
		  resolution: 16        // optional
		});
		csg = csg.union(sphere); 
	}
	return csg;
}