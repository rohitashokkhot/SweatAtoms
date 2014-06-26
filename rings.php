var br = [0,0,0,1,1,1,0];

function main()
{
  var resolution = 24; // increase to get smoother corners (will get slow!)
   var result=new CSG();
 var bars=new CSG();
   var cyl=CSG.cylinder({start:[0,0,0], end:[0,0,10], radius:12, resolution:resolution});
	 
	  var centerhole = CSG.cylinder({start: [0,0,0], end: [0,0,10], radius: 10, resolution:resolution});
var length=7;
for (i=0; i<length; i++)
{
    var angle=i*360/length;
    var x = Math.cos(angle * Math.PI/180) * 12;
    var y = Math.sin(angle * Math.PI/180) * 12;
    //var random2 = Math.floor((Math.random()*10)+1);
	random2=br[i]+1;
    var bar=CSG.cylinder({start:[x,y,0], end:[x,y,random2], radius:2, resolution:resolution});
    bars = bars.union(bar);
}
	   cyl = cyl.subtract(centerhole);
cyl = cyl.subtract(bars);
result = cyl;
  return result;
}