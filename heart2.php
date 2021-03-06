

function B1(t) { return t*t*t }
function B2(t) { return 3*t*t*(1-t) }
function B3(t) { return 3*t*(1-t)*(1-t) }
function B4(t) { return (1-t)*(1-t)*(1-t) }



function bezier2bars(x1,y1,x2,y2,x3,y3,x4,y4,count,beat)
{
   var x=0; var y=0;
   csg = new CSG();
   if(count==2)
   {
		x = x1*B1(i) + x2*B2(i) + x3*B3(i) + x4*B4(i);
   	 	y = y1*B1(i) + y2*B2(i) + y3*B3(i) + y4*B4(i);
	    var path1 = new CSG.Path2D([[75,40], [x,y]],true);
	    var csg1 = path1.rectangularExtrude(2, 10, 16, true);
		csg = csg.union(csg1);
		var midx = (x * beat2 + 75 * beat1)/(beat1+beat2);
		var midy = (y* beat2 + 40 * beat1)/(beat1+beat2);
		var dot = CSG.cylinder({
		  start: [midx, midy, 0],
		  end: [midx, midy, 10],
		  radius: 3,
		  resolution: 16
		});
		csg =csg.union(dot);
	   }
   }
   else if(count==3)
   {
	   for (i=1; i>=0.5; i-=0.5)
	   {
   		x = x1*B1(i) + x2*B2(i) + x3*B3(i) + x4*B4(i);
   	 	y = y1*B1(i) + y2*B2(i) + y3*B3(i) + y4*B4(i);
	    var path1 = new CSG.Path2D([[75,40], [x,y]],true);
	    var csg1 = path1.rectangularExtrude(2, 10, 16, true);
		csg = csg.union(csg1);
		var midx = (x * 2 + 75 * 1)/3;
		var midy = (y*2 + 40 * 1)/3;
		var dot = CSG.cylinder({
		  start: [midx, midy, 0],
		  end: [midx, midy, 10],
		  radius: 3,
		  resolution: 16
		});
		csg =csg.union(dot);
	   }
   }
   return csg;
}

function bezier(x1,y1,x2,y2,x3,y3,x4,y4)
{
   var x=0; var y=0;
   var points = [];
   for (i=0; i<=1; i+=0.1)
  	{
   		x = x1*B1(i) + x2*B2(i) + x3*B3(i) + x4*B4(i);
   	 	y = y1*B1(i) + y2*B2(i) + y3*B3(i) + y4*B4(i);
   	 	var vec= new CSG.Vector2D(x,y);
   	 	points.push(vec);
   	 }
	var path3 = new CSG.Path2D(points,false);
	var csg3 = path3.rectangularExtrude(3, 10, 16, true);
	return csg3;
}


function main()
{
  var resolution = 16; // increase to get smoother corners (will get slow!)
  var res= heart();
  var bars=addbars();
  res = res.union(bars);
  var shed = new CSG.Path2D([[75,40], [75,25]],false);
  var csg = shed.rectangularExtrude(3, 10, 16, true);
  var hold = CSG.cylinder({
  start: [75, 23, 3],
  end: [75, 23, 7],
  radius: 4,
  resolution: 16
});
  var hold2 = CSG.cylinder({
  start: [75, 23, 3],
  end: [75, 23, 7],
  radius: 3,
  resolution: 16
});
  
  hold = hold.subtract(hold2);
  res = res.union(csg);
  res = res.union(hold);
  return res;
}

function addbars()
{
    var result=new CSG();
    var pie1= bezier2bars(75,40,75,37,70,25,50,25,1);
    pie1=pie1.translate([0,0,0]);
    var pie2= bezier2bars(50,25,20,25,20,62.5,20,62.5,2);
    pie2=pie2.translate([0,0,0]);
    var pie3= bezier2bars(20,62.5,20,80,40,102,75,120,2);
    pie3=pie3.translate([0,0,0]);
    var pie4=bezier2bars(75,120,110,102,130,80,130,62.5,3);
     pie4=pie4.translate([0,0,0]);
    var pie5=bezier2bars(130, 62.5,130,62.5,130,25,100,25,3);
     pie5=pie5.translate([0,0,0]);
    var pie6 = bezier2bars(100,25,85,25,75,37,75,40,1);
     pie6=pie6.translate([0,0,0]); 
    result=result.union(pie1);
    result=result.union(pie2);
    result=result.union(pie3);
    result=result.union(pie4);
    result=result.union(pie5);
    result=result.union(pie6);
    return result;
	
}

function heart() 
{
  var result=new CSG();
  var pie1= bezier(75,40,75,37,70,25,50,25);
  pie1=pie1.translate([0,0,0]);
  var pie2= bezier(50,25,20,25,20,62.5,20,62.5);
  pie2=pie2.translate([0,0,0]);
  var pie3= bezier(20,62.5,20,80,40,102,75,120);
  pie3=pie3.translate([0,0,0]);
  var pie4=bezier(75,120,110,102,130,80,130,62.5);
   pie4=pie4.translate([0,0,0]);
  var pie5=bezier(130, 62.5,130,62.5,130,25,100,25);
   pie5=pie5.translate([0,0,0]);
  var pie6 = bezier(100,25,85,25,75,37,75,40);
   pie6=pie6.translate([0,0,0]); 
  result=result.union(pie1);
  result=result.union(pie2);
  result=result.union(pie3);
  result=result.union(pie4);
  result=result.union(pie5);
  result=result.union(pie6);
  return result;
}

