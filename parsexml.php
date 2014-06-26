<?php
	$xml=simplexml_load_file("kit23jul7hr.xml");
	//$xml=simplexml_load_file("abc.xml");
	$string= (string)$xml->{"calendar-items"}->exercise->result->samples->sample->values;
	$resting= (string)$xml->{"calendar-items"}->exercise->result->{"user-settings"}->{"heart-rate"}->resting;
	$maximum= (string)$xml->{"calendar-items"}->exercise->result->{"user-settings"}->{"heart-rate"}->maximum;
	$uzone = array();
	$lzone = array();
	for($i=1;$i<6;$i++)
	{
		$uzone[$i]= (string)$xml->{"calendar-items"}->exercise->result->zones->zone[$i-1]->upper;
		$lzone[$i]= (string)$xml->{"calendar-items"}->exercise->result->zones->zone[$i-1]->lower;
	}
	$uzone [0]= $lzone[1];
	$lzone [0] = $resting;
	
	echo $string;
	$sarray= explode(',',$string);
	$minarray = array();
	$hourarray= array();
	$slength= sizeof($sarray);echo $slength;
	echo "<div id='hbeats-all'>[";
	for ($i=0;$i<$slength;$i++)
	{
		if($i==$slength-1)
			echo "[". $i. ",".$sarray[$i]. "]";
		else
			echo "[". $i. ",".$sarray[$i]. "],";		
	}
	echo "]</div> <br/>";
	$k=0; $count= floor($slength/60); echo $count;
	$fill = $slength%60; echo "fill:".$fill;
	$slength=$slength-$fill;
	echo "<div id='hbeats-permin'>[";
	for ($i=0;$i<$slength;$i+=60)
	{
		//echo "<br>";
		$arrays=array();
		$arrays= array_slice($sarray,$i,60);
		//print_r($arrays[$i]);
		//echo "</pre>";
		$arraysum= array_sum($arrays)/60;
		if($k==$count-1)
			echo "[". $k. ",".floor($arraysum). "]";
		else
			echo "[". $k. ",".floor($arraysum). "],";
		$minarray[$k]= floor($arraysum);
		$k++;
		//echo "SUM:". floor($arraysum);
	}
	echo "]</div> <br/>";
	echo "<div id='hbeats-perminreduced'>[";
	$reducedsize = floor($k/120)+1;
	echo "REDUCED". $reducedsize;
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
		if($k==$count-1)
			echo "[". $k. ",".floor($arraysum). "]";
		else
			echo "[". $k. ",".floor($arraysum). "],";
		$reducedarray[$k]= floor($arraysum);
		$k++;
		//echo "SUM:". floor($arraysum);
	}
	echo "]</div> <br/>";
	
	$k=0;$count= floor($slength/3600); echo $count;
	$fill = $slength%3600; echo "fill:".$fill;
	$slength=$slength-$fill;
	echo "<div id='hbeats-perhour'>[";
	for ($i=0;$i<$slength;$i+=3600)
	{
		//echo "<br>";
		$arrays= array_slice($sarray,$i,3600);
		//print_r($arrays[$i]);
		//echo "</pre>";
		$arraysum= array_sum($arrays)/3600;
		if($k==$count-1)
			echo "[". $k. ",".floor($arraysum). "]";
		else
			echo "[". $k. ",".floor($arraysum). "],";
		//echo "SUM:". floor($arraysum);
		$hourarray[$k]= floor($arraysum);
		$k++;
	}
	echo "]</div><br/>";
	$gap= 20; $k=1;
	$recorded = $sarray[0];
	echo "<div id='hbeats-perpeak'>";
	echo $sarray[0];
	for ($i=1;$i<$slength;$i++)
	{
		if(($sarray[$i]>=($recorded+$gap))||($sarray[$i]<($recorded- $gap)))
		{
			echo ",".$sarray[$i];
			$k++;	
			$recorded = $sarray[$i];		
		}		
	}
	echo "</div><br/>";
	$gap= 15; $k=1;
	$recorded = $minarray[0];
	$slength= sizeof($minarray);
	echo "<div id='hbeats-minperpeak'>";
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
	echo "</div><br/>";
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
	}
	echo "]</div><br/>";
	$slength= sizeof($sarray);
	$k=0;$count= floor($slength/3600); echo $count;
	$fill = $slength%3600; echo "fill:".$fill;
	$slength=$slength-$fill;
	$diff= $maximum-$resting; 
	echo "<div id='hbeats-modeperhour'>[";
	for ($i=0;$i<$slength;$i+=3600)
	{
		//echo "<br>";
		$arrays= array_slice($sarray,$i,3600);
		//print_r($arrays[$i]);
		//echo "</pre>";
		$values = array_count_values($arrays); 
		$mode = array_search(max($values), $values);
		$modediff=$mod-$resting;
		//$arraysum= array_sum($arrays)/3600;
		if($k==$count-1)
			echo "[". $k. ",".$mode. "]";
		else
			echo "[". $k. ",".$mode. "],";
		//echo "SUM:". floor($arraysum);
		//$hourarray[$k]= $mode;
		$k++;
	}
	echo "</div><br/>";
	$diff= $maximum-$resting;
	echo $diff;
?>