//var br = [4,3.5,3.5,3.5,3.5,3.5,3.5,4,4,4,4,4];
var br = [5,3.5,5,5,2,5,1.5,5,3,4,5];
function main()
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
   		   //var shed = new CSG.Path2D([[x,y,br[i]], [x,y,br[i]+1]],false);
   		   //var csg = shed.rectangularExtrude(2, 14, 20, true);
	 	  var hole2 = CSG.cylinder({start:[x,y,0], end:[x,y, 3], radius:br[i]-1, resolution:resolution});
		  cyl2 = cyl2.subtract(hole2);
        cyl3 = cyl3.union(cyl2);
		
    	}
}
cyl3 = cyl3.union(cyl);
cyl3 = cyl3.subtract(centerhole);
return cyl3;

}