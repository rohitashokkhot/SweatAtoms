<?php
require('lib/sfYamlParser.php');
require('lib/runkeeperAPI.class.php');
$rkAPI = new runkeeperAPI('config/rk-api.sample.yml');
if ($rkAPI->api_created == false) {
	echo 'error '.$rkAPI->api_last_error; /* api creation problem */
	exit();
}
if ($_GET['token'])
{
	$token = $_GET['token'];
	//$rkAPI->access_token = $token;
	$new= $rkAPI->setRunkeeperToken($token);
	//echo $new;
	//echo "Access token:".$rkAPI->access_token;
	$rkActivities = $rkAPI->doRunkeeperRequest('FitnessActivityFeed','Read');
	if ($rkActivities) {
		//echo "<pre>". print_r($rkActivities). "</pre>";
		$uri = $rkActivities->items[0]->uri;
		//echo $uri;
		//echo "<br><pre>". json_encode($rkActivities). "</pre>";
		
	}
	else {
		echo $rkAPI->api_last_error;
		print_r($rkAPI->request_log);
	}

	$rkActivities = $rkAPI->doRunkeeperRequest('FitnessActivity','Read', '' ,$uri);
	if ($rkActivities) {
		echo "<pre>". print_r($rkActivities). "</pre>";
		$count = sizeof($rkActivities->heart_rate);
		echo "<span id='count'>".$count. "</span><br>";
		$prints="[";
		$recorded = $rkActivities->heart_rate[0]->heart_rate;
		$timestamp = $rkActivities->heart_rate[0]->timestamp;
		//$past = ($timestamp >= 3600) ? date('G', $timestamp).':' : '';
		//$past .= intval(date('i',$timestamp)).':'.date('s', $timestamp);
		$past = intval(date('i',$timestamp));
		$gap=5; $k=1;
		$hr = array(); 
		$ts = array();
		$hr[0]= $recorded;
		$ts[0]=$past;
		for ($i=1;$i<$count;$i++)
		{
			
			$hrate = $rkActivities->heart_rate[$i]->heart_rate;
			
			$tstamp = $rkActivities->heart_rate[$i]->timestamp;
			//$time = ($ts >= 3600) ? date('G', $ts).':' : '';
			//$time .= intval(date('i',$ts)).':'.date('s', $ts);
			$time = intval(date('i',$tstamp));
			//if((($hrate>= $recorded+$gap)||($hrate<= $recorded-$gap))&&($time != $past))
			if($time != $past)
			{ 
				$recorded = $hrate;
				$hr[$k]=$recorded;
				$ts[$k]=$time;
				$k++;
				$past=$time;
			}
		}
		for ($i=0;$i<$k;$i++)
		{
			$hr[$i]=$hr[$i]-40;
			if($i!=$count-1)
			{
				//$printhr = $printhr.$hr. ",";
				$printts = $printts."[".$ts[$i]. ",". $hr[$i]. "],";
			}
			else
			{
				//$printhr = $printhr.$hr;
				$printts = $printts."[".$ts[$i]. ",". $hr[$i]. "]";
			}
			
		}
		//echo "<span id='hr'>".$printhr. "</span>";
		echo "<span id='hr'>".$printts. "</span>";
		//echo "<br><pre>". json_encode($rkActivities). "</pre>";
		
	}
	
}
?>

