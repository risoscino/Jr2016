<?php


// Programmers notes:
// I was not able to succeed in completing this entirely,
// There are oddities in the visual aspect of the hourglass, as well as user cases
// that do not work correctly
// But the percentages of sand in each bulb are correct
// I started late ):


// get my values from angular post
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$percent = $request->percent;
$height = $request->height;
$hourglass= buildHourglass ($height, $percent);
// With this set up,  this echo returns the hourglass to angular
echo $hourglass;
function buildHourglass ($height, $percent) {
	// The whole height of the hour glass,  user input for height,
	// times 2 for top bulb, bottom bulb, and then + 2 for the top and bottom of the bulm
	$hourglass = $height * 2 + 2;
	$rowCharacters = 1;
	$top = "";
	$backSlash = "\\";
	$forwardSlash = "/";
	$margin = " ";
	$mid = "";
	$heightTop = $height + 1;
	//Total characters for top/bottom bulb
	$middleArea = 0;

	// Find the total area, as well as the length of each row
	for ($g = 1; $g < $height; $g++) {
			$rowCharacters += 2;
			$middleArea += $rowCharacters;
	}
	// percent expressions
	$percentFill = round(($middleArea * $percent) / 100);
	$bottomPercent = $middleArea - $percentFill;

	// Loop through each row of the hourglass
	for ($i = $hourglass; $i != 0; $i--) {
		// Create the top of the top bulb, which in this case is the first iteration of loop
		if ($i == $hourglass) {
			for ($l = 0; $l < $rowCharacters; $l++) {
					$top .= "_";
				}
				$top .= "\r\n";
		} else {
			// If we are past the top of the bulb
			if ($i > $heightTop) {
				//This takes care of the margin the lamp edge needs to create a cone like shape
				$margin .= "&nbsp&nbsp";
				// Add the left edge of the hourglass
				$mid .= $margin . $backSlash;

				// Looping through each row, to figure if we need sand, or empty characters
				for ($l = 0; $l < $rowCharacters; $l++) {
					if ($percentFill >= $middleArea - $rowCharacters) {
						if ($percentFill != 0) {
							$mid .= "x";
							$percentFill--;
						}
					} else {
						$mid .= "&nbsp&nbsp";
					}
				}
				$middleArea -= $rowCharacters;
				$rowCharacters -= 2;
				$mid .= $forwardSlash . "\r\n";

			} elseif ($i == $heightTop) {
				$rowCharacters += 2;
				$middleArea += $rowCharacters;
			} else {
				// Now we are working with the bottom bulb of the hourglass
				// I reload the area of this bulb with the characters in the row
				$middleArea += $rowCharacters;

				// Add the edge of the bulb
				$mid .= $margin . $forwardSlash;
				$margin = preg_replace('/&nbsp&nbsp/', '', $margin, 1);
				for ($l = 0; $l < $rowCharacters; $l++) {

					if ($bottomPercent <= $middleArea + $rowCharacters) {
						if($bottomPercent != 0) {
							$mid .= "x";
							$bottomPercent --;
						} else {
							$mid .= "&nbsp&nbsp";
						}
					} else {
						$mid .= "&nbsp&nbsp";
					}
				}
				$rowCharacters += 2;
				$mid .= $backSlash .  "\r\n";
			}
		}

	}
	return nl2br($top . $mid);
}
?>

