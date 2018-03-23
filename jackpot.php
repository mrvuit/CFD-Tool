<?php

	$jackpot = null;
	for ($i=1; $i <= 6; $i++) { 
		$lucky_number = rand(1,55);
		if($lucky_number < 10)
			$lucky_number = '0'.$lucky_number;
		if($i == 6) {
			$jackpot .= $lucky_number;
			break;
		}
		$jackpot .= $lucky_number.' - ';
	}
	echo $jackpot;