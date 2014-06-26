<?php
//if ($_GET['choice'])
//{
$token = $_GET['choice'];
$file = $_GET['file'];
//$token=0;
$xml=simplexml_load_file($file);
//$xml=simplexml_load_file("carol08081.xml");
$string= (string)$xml->{"calendar-items"}->exercise->result->samples->sample->values;
$resting= (string)$xml->{"calendar-items"}->exercise->result->{"user-settings"}->{"heart-rate"}->resting;
$maximum= (string)$xml->{"calendar-items"}->exercise->result->{"user-settings"}->{"heart-rate"}->maximum;
$uzone = array();
$lzone = array();

//------------------------------------------------
// create zone array from the polar beat application: explicitly entered
for($i=1;$i<6;$i++)
{
	$uzone[$i]= (string)$xml->{"calendar-items"}->exercise->result->zones->zone[$i-1]->upper;
	$lzone[$i]= (string)$xml->{"calendar-items"}->exercise->result->zones->zone[$i-1]->lower;
}
$uzone [0]= $lzone[1];
$lzone [0] = $resting;
//------------------------------------------------
$sarray= explode(',',$string);
$minarray = array();
$hourarray= array();
$slength= sizeof($sarray);//echo $slength;
$k=0; 
$count= floor($slength/60); //echo $count;
$fill = $slength%60; //echo "fill:".$fill;
$slength=$slength-$fill;
$mincount = $count;
//------------------------------------------------
// used for all options : basic minute reading
echo "<div id='beats-min'>["; 
for ($i=0;$i<$slength;$i+=60)
{
	$arrays=array();
	$arrays= array_slice($sarray,$i,60);
	$arraysum= array_sum($arrays)/60;
	if($arraysum<=30)
		$arraysum=$resting;
	if($arraysum>=200)
		$arraysum=$maximum;
	if($k==$count-1)
		echo "[". $k. ",".floor($arraysum). "]";
	else
		echo "[". $k. ",".floor($arraysum). "],";
	$minarray[$k]= floor($arraysum);
	$k++;
}
echo "]</div> <br/>";		
//------------------------------------------------

//------------------------------------------------
// used for option: 0 : graph
if($token==0)
{
	echo "<div id='beats-graph'>[";
	$printbedsize = 110;
	$rsize = floor($k/$printbedsize)+1; 
	//echo "RSIZE". $rsize;
	$n = $k;
	$k=0;
	//$k = -20;
	$rminarray = array();
	for ($i=0;$i<$n;$i+=$rsize)
	{
		$arrays=array();
		$arrays= array_slice($minarray,$i,$rsize);
		$arraysum= (array_sum($arrays)/$rsize);
		if($arraysum<=30)
			$arraysum=$resting;
		if($arraysum>=200)
			$arraysum=$maximum;
		if($k==$n-1)
			echo "[". $k. ",".floor($arraysum). "]";
		else
			echo "[". $k. ",".floor($arraysum). "],";
		$rminarray[$k]= floor($arraysum);
		$k++;
	}
	echo "]</div> <br/>";	
}
//------------------------------------------------

//------------------------------------------------
// used for option: 1 : frog
else if ($token==1)
{
	
}	
//------------------------------------------------

//------------------------------------------------
// used for option: 2 : flower

else if ($token==2)
{
	$gap= 20; $k=1;
	$recorded = $minarray[0];
	$slength= sizeof($minarray);
	echo "<div id='beats-flower'>[";
	echo floor($minarray[0]/5);
	for ($i=1;$i<$slength;$i++)
	{
		if(($minarray[$i]>=($recorded+$gap))||($minarray[$i]<($recorded- $gap)))
		{
			
			echo ",".floor($minarray[$i]/5);
			$k++;	
			$recorded = $minarray[$i];		
		}		
	}
	echo "]</div>";
}
//------------------------------------------------

//------------------------------------------------
// used for option: 3 : dice

else if ($token==3)
{
	$gap= 15; $k=1;
	$recorded = $minarray[0];
	$slength= sizeof($minarray);
	echo "<div id='beats-dice'>[";
	$zonearray = array(0,0,0,0,0,0);
	sort($minarray); 
	for ($i=0;$i<$slength;$i++)
	{
		for($j=0;$j<6;$j++)
		{
			if(($minarray[$i]>= $lzone[$j])&&($minarray[$i]<$uzone[$j]))
				$zonearray[$j]++;
		}
	}
	for($j=0;$j<6;$j++)
	{
		$percent = floor(($zonearray[$j]/$slength)* 100);
		$number = (ceil($percent / 10) * 10)/20 + 1;
		if($j!=5)
			echo $number. ",";
		else
			echo $number;
	}
	echo "]</div><br/>";
}
//------------------------------------------------

//------------------------------------------------
// used for option: 4 : bubbles

else if ($token==5)
{
	$k=0;$n=0;
	$slength= sizeof($sarray);
	//echo $slength."###";
	$count= floor($slength/600); //echo $count;
	$fill = $slength%600; //echo "fill:".$fill;
	$hcount=$count;
	//echo $hcount;
	$active = floor(0.5 * $maximum);
	//echo $active;
	$activearray = array_fill(0, $hcount, 0);
	//echo $percent;

	for($i=0;$i<$mincount;$i++)
	{
		if($minarray[$i]>$active)
			$activearray[$k]++;	
		if($i%60==0&&$i!=0)
		{
			$k++;
		}
	}
	echo "<div id='beats-bubbles'>[";
	for($i=0;$i<$hcount;$i++)
	{
		$number = (ceil($activearray[$i] / 10) * 10)/20 + 1;
		if($i!=$hcount-1)
			echo $number.",";
		else
			echo $number;
	}
	echo "]</div><br/>";		
}

//------------------------------------------------
// used for option: 4 : bubbles

else if ($token==4)
{
	$k=0;$n=0;
	$slength= sizeof($sarray);
	//echo $slength."###";
	$count= floor($slength/3600); //echo $count;
	$fill = $slength%3600; //echo "fill:".$fill;
	$hcount=$count;
	//echo $hcount;
	$active = floor(0.5 * $maximum);
	//echo $active;
	$activearray = array_fill(0, $hcount, 0);
	//echo $percent;

	for($i=0;$i<$mincount;$i++)
	{
		if($minarray[$i]>$active)
			$activearray[$k]++;	
		if($i%60==0&&$i!=0)
		{
			$k++;
		}
	}
	echo "<div id='beats-bubbles'>[";
	for($i=0;$i<$hcount;$i++)
	{
		$number = (ceil($activearray[$i] / 10) * 10)/20 + 1;
		if($i!=$hcount-1)
			echo $number.",";
		else
			echo $number;
	}
	echo "]</div><br/>";		
}

	
?>