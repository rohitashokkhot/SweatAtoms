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
		for ($i=0;$i<$count;$i++)
		{
			$hr = $rkActivities->heart_rate[$i]->heart_rate;
			$ts = $rkActivities->heart_rate[$i]->timestamp;
			$seconds= $ts;
			$time = ($seconds >= 3600) ? date('G', $seconds).':' : '';
			$time .= intval(date('i',$seconds)).':'.date('s', $seconds);
			if($i!=$count-1)
			{
				//$printhr = $printhr.$hr. ",";
				$printts = $printts."[".$ts. ",". $hr. "],";
			}
			else
			{
				//$printhr = $printhr.$hr;
				$printts = $printts."[".$ts. ",". $hr. "]";
			}
			
		}
		//echo "<span id='hr'>".$printhr. "</span>";
		echo "<span id='hr'>".$printts. "</span>";
		//echo "<br><pre>". json_encode($rkActivities). "</pre>";
		
	}
	
}
?>

