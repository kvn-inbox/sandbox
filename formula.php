<?php

$brokers[0] = array(
	'name' => 'br #0',
	'conversionRateLH' => 0.75,
	'conversionRateL3H' => 0.75,
	'conversionRateL12H' => 0.75,
	'conversionRateL24H' => 0.75,
	'conversionRateLW' => 0.75,
	'conversionRateLM' => 0.75,
	'conversionRateHD1d' => 0.75,
	'conversionRateHD7d' => 0.75,
	'conversionRateHD30d' => 0.75,
	'brokerStatWeight' => 0,
);


$brokers[1] = array(
	'name' => 'br #1',
	'conversionRateLH' => 0.35,
	'conversionRateL3H' => 0.35,
	'conversionRateL12H' => 0.35,
	'conversionRateL24H' => 0.35,
	'conversionRateLW' => 0.35,
	'conversionRateLM' => 0.35,
	'conversionRateHD1d' => 0.35,
	'conversionRateHD7d' => 0.35,
	'conversionRateHD30d' => 0.35,
	'brokerStatWeight' => 0,
);

$brokers[2] = array(
	'name' => 'br #2',
	'conversionRateLH' => 0.55,
	'conversionRateL3H' => 0.55,
	'conversionRateL12H' => 0.55,
	'conversionRateL24H' => 0.55,
	'conversionRateLW' => 0.55,
	'conversionRateLM' => 0.55,
	'conversionRateHD1d' => 0.55,
	'conversionRateHD7d' => 0.55,
	'conversionRateHD30d' => 0.55,
	'brokerStatWeight' => 0,
);

function make_seed()
{

  list($usec, $sec) = explode(' ', microtime());
    return $sec + $usec * 1000000;
}


function getrand($min, $max) {
	// TODO: give only 25%

	mt_srand(make_seed());
	if (mt_rand(1,250) == 10) {

		$randval = mt_rand($min*100, $max*100)/100;
		// echo '*'.$randval."*";
		return $randval;
	} else {
		return 0;
	}

}

function calcWeight($brokers) {
	foreach ($brokers as $id=>$b) {
		// OLD FORMULA
		// $w = (50 * $b['conversionRateLH'] + 20 * $b['conversionRateL3H'] + 15 * $b['conversionRateL12H'] + 10 * $b['conversionRateL24H'] + 5 * $b['conversionRateLW'] + 30 * $b['conversionRateLM'] + 5 * $b['conversionRateHD1d'] + 10 * $b['conversionRateHD7d'] + 15 * $b['conversionRateHD30d']) * $b['conversionRateLH'] / $b['conversionRateLM'];

		$w = ( (($b['conversionRateLH'] < 0.1) ? 50 * $b['conversionRateLM'] : 50 * $b['conversionRateLH']) + 20  * $b['conversionRateL3H'] + 15 * $b['conversionRateL12H'] + 10 * $b['conversionRateL24H'] + 5 * $b['conversionRateLW'] + 30 * $b['conversionRateLM'] + 5 * $b['conversionRateHD1d'] + 10 * $b['conversionRateHD7d'] + 15 * $b['conversionRateHD30d']) * (((($b['conversionRateLH'] < 0.1) ? getrand(0, $b['conversionRateLM']) : $b['conversionRateLH']))/ $b['conversionRateLM']);
		$brokers[$id]['brokerStatWeight']  = $w;
	}

	return $brokers;
}


print_r($brokers);
$bb = calcWeight($brokers);
print_r($bb);

function print_b($brokers) {
	foreach ($brokers as $b) {
		echo $b['name'].' - conversionRateLH: '.$b['conversionRateLH'].', brokerStatWeight: '.$b['brokerStatWeight']."\n";
	}
}

print_b($bb);



// Some tests

for ($conversionRateLH = 0; $conversionRateLH<0.95; $conversionRateLH+=0.1) {
	echo ' -- '.$conversionRateLH. ' --'."\n";
	$brokers[0]['conversionRateLH'] = $conversionRateLH;
	$bb = calcWeight($brokers);
	print_b($bb);
}

$ff=0;
$max_kkk = 0;
for ($ff=0; $ff<1000; $ff++) {
		$kkk=0;
		for ($k=0; $k<1000; $k++) {
			$brokers[0]['conversionRateLH'] = 0;
			$bb = calcWeight($brokers);
				// echo $k.', brokerStatWeight = '.$bb[0]['brokerStatWeight']."\n";
			 if ($bb[0]['brokerStatWeight'] > 88) {
				// echo '--------'.$k.', brokerStatWeight = '.$bb[0]['brokerStatWeight']."\n";
				$kkk++;
			 }
		}
		echo 'percent: '.($kkk/$k).' ('.$kkk.')'."\n";
		if ($kkk > $max_kkk) {
			$max_kkk = $kkk;
		}
}


echo "Max percent: ".$max_kkk."\n";

?>
