<?php
//if ($_GET['choice'])
//{
	//$token = $_GET['choice'];
	$token=4;
	$xml=simplexml_load_file("robert-19jul-7hrs.xml");
	//$xml=simplexml_load_file("helmut22jul7hr.xml");
	//$xml=simplexml_load_file("kit23jul7hr.xml");
	$string= (string)$xml->{"calendar-items"}->exercise->result->samples->sample->values;
	$resting= (string)$xml->{"calendar-items"}->exercise->result->{"user-settings"}->{"heart-rate"}->resting;
	$maximum= (string)$xml->{"calendar-items"}->exercise->result->{"user-settings"}->{"heart-rate"}->maximum;
	$uzone = array();
	$lzone = array();
	//echo $string;
	for($i=1;$i<6;$i++)
	{
		$uzone[$i]= (string)$xml->{"calendar-items"}->exercise->result->zones->zone[$i-1]->upper;
		$lzone[$i]= (string)$xml->{"calendar-items"}->exercise->result->zones->zone[$i-1]->lower;
	}
	$uzone [0]= $lzone[1];
	$lzone [0] = $resting;
	$sarray= explode(',',$string);
	$minarray = array();
	$hourarray= array();
	$slength= sizeof($sarray);//echo $slength;
		/*echo "<div id='beats1'>[";
		for ($i=0;$i<$slength;$i++)
		{
			if($i==$slength-1)
				echo "[". $i. ",".$sarray[$i]. "]";
			else
				echo "[". $i. ",".$sarray[$i]. "],";		
		}
		echo "]</div> <br/>";*/
		$k=0; $count= floor($slength/60); //echo $count;
		$fill = $slength%60; //echo "fill:".$fill;
		$slength=$slength-$fill;
		echo "<div id='beats-min'>[";
		for ($i=0;$i<$slength;$i+=60)
		{
			//echo "<br>";
			$arrays=array();
			$arrays= array_slice($sarray,$i,60);
			//print_r($arrays[$i]);
			//echo "</pre>";
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
			//echo "SUM:". floor($arraysum);
		}
		echo "]</div> <br/>";		
		echo "<div id='hbeats-minreduced'>[";
		$reducedsize = floor($k/120)+1;
		//echo "REDUCED". $reducedsize;
		$n = $k;
		$k=0;
		$reducedarray = array();
		for ($i=0;$i<$n;$i+=$reducedsize)
		{
			//echo "<br>";
			$arrays=array();
			$arrays= array_slice($sarray,$i,$reducedsize);
			//print_r($arrays[$i]);
			//echo "</pre>";
			$arraysum= array_sum($arrays)/$reducedsize;
			if($arraysum<=30)
				$arraysum=$resting;
			if($arraysum>=200)
				$arraysum=$maximum;
			if($k==$count-1)
				echo "[". $k. ",".floor($arraysum). "]";
			else
				echo "[". $k. ",".floor($arraysum). "],";
			$reducedarray[$k]= floor($arraysum);
			$k++;
			//echo "SUM:". floor($arraysum);
		}
		echo "]</div> <br/>";
	if ($token==4)
	{
		$k=0;$count= floor($slength/3600); //echo $count;
		$fill = $slength%3600; //echo "fill:".$fill;
		$slength=$slength-$fill;
		echo "<div id='hours'>[";
		for ($i=0;$i<$slength;$i+=3600)
		{
			//echo "<br>";
			$arrays= array_slice($sarray,$i,3600);
			//print_r($arrays[$i]);
			//echo "</pre>";
			$arraysum= array_sum($arrays)/3600;
			//$arraysum = $arraysum- $resting;
			$percent = floor(($arraysum/$maximum)* 100);
			$number = (ceil($percent / 10) * 10)/20 + 1;
			if($k==$count-1)
				//echo "[". $k. ",".$number. "]";
			echo $number;
			else
				//echo "[". $k. ",".$number. "],";
			echo $number.",";
			//echo "SUM:". floor($arraysum);
			$hourarray[$k]= floor($arraysum);
			$k++;
		}
		echo "]</div><br/>";		
	}
	else if ($token==2)
	{
		$gap= 15; $k=1;
		$recorded = $minarray[0];
		$slength= sizeof($minarray);
		echo "<div id='beats-peak'>[";
		//echo "[0,".$minarray[0]. "]";
		echo $minarray[0];
		for ($i=1;$i<$slength;$i++)
		{
			if(($minarray[$i]>=($recorded+$gap))||($minarray[$i]<($recorded- $gap)))
			{
				
				echo ",".$minarray[$i];
				$k++;	
				$recorded = $minarray[$i];		
			}		
		}
		echo "]</div>";
	}
	else if ($token==3)
	{
		$gap= 15; $k=1;
		$recorded = $minarray[0];
		$slength= sizeof($minarray);
		echo "<div id='hbeats-zones'>[";
		//echo "[0,".$minarray[0]. "]";
		$zonearray = array(0,0,0,0,0,0);
		sort($minarray); //$sortarray =
		for ($i=0;$i<$slength;$i++)
		{
			//echo $minarray[$i]. ",";
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
				//echo $zonearray[$j]. ",";
			echo $number. ",";
			else
				echo $number;
			//echo $zonearray[$j];
		}
		echo "]</div><br/>";
	}
	//}
	
?>