<?php
	#Trim trailing '%' from second argument
	$argv[2] = rtrim($argv[2], "%");
	
	#Check if arguments are numeric
	if(!is_numeric($argv[1]) || !is_numeric($argv[2])) {
		exit("Error: parameters must be numeric\n");
	}

	#Check if arguments are wihtin range
	if($argv[1] <= 1 || $argv[2] > 100 || $argv[2] < 0) {
		exit("Error: arg1 must be greater than 1 and arg2 must be between 0 and 100 inclusive\n");
	}

	$bulbHeight = (int)$argv[1];
	$topBulbSandAmount = ceil($argv[2]);

	$hourglassTop = str_repeat('_', $bulbHeight * 2 + 1)."\n";
	$bulb = "";

	#Total Bulb Capacity
	$bulbCapacity = pow($bulbHeight, 2);
	#Amount of empty space in top bulb
	$emptySpace = $bulbCapacity - ceil($bulbCapacity * ($topBulbSandAmount/100));

	#Character representing sand in hourglass
	$sandChar = "x";

	for($count = $bulbHeight; $count > 0; $count--) {
		$leadingWhiteSpace = str_repeat(" ", $bulbHeight - $count);
		$bulbLevel = "{$leadingWhiteSpace}\\";
		$bulbLevelCapacity = $count * 2 - 1;

		if ($count == $bulbHeight) {
			$sandChar = "y";
		}
		else {
		    $sandChar = "x";
		}

		#If bulb level is completely empty
		if($emptySpace >= $bulbLevelCapacity) {
			$bulbLevel .= str_repeat("_", $bulbLevelCapacity);
			$bulbLevel .= "/{$leadingWhiteSpace}\n";
			$emptySpace -= ($bulbLevelCapacity);
			$bulb .= $bulbLevel;
			continue;
		} 
		  
		#If bulb level is partially empty     
		if($emptySpace != 0) {
			$remainingSand = $bulbLevelCapacity- $emptySpace;

			#Amount of sand near left & right edge of bulb
			$leftEdgeCapacity = floor($remainingSand/2);
			$rightEdgeCapacity = ceil($remainingSand/2);
			  
			$bulbLevel .= str_repeat($sandChar, $leftEdgeCapacity);
			$bulbLevel .= str_repeat("_", $emptySpace);
			$bulbLevel .= str_repeat($sandChar, $rightEdgeCapacity);
			$bulbLevel .=	"/{$leadingWhiteSpace}\n";
			$emptySpace = 0;
			$bulb .= $bulbLevel;
		}
		#if bulb level is completely full
		else {
			$bulbLevel .= str_repeat($sandChar, $bulbLevelCapacity);
			$bulbLevel .= "/{$leadingWhiteSpace}\n";
            $bulb .= $bulbLevel;
		}
	}
	
	#Replace characters to create top bulb
	$topBulb = str_replace("_", " ", $bulb);
	$topBulb = str_replace("y", "x", $topBulb);

	#Reverse and replace characters to create bottom bulb
	$bottomBulb = str_replace("x", " ", strrev($bulb));
	$bottomBulb = str_replace("_", "x", $bottomBulb);
	$bottomBulb = str_replace("y", "_", $bottomBulb);
	$bottomBulb = ltrim($bottomBulb, "\n");


	$hourglass = "Input: {$bulbHeight} {$topBulbSandAmount}%\nOutput:\n{$hourglassTop}{$topBulb}{$bottomBulb}\n";

	echo $hourglass;
?>