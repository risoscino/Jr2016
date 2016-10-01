<?php

class Hourglass
{
    private $height = 2;
    private $capacity = 1;

    /**
     * Constructor.
     *
     * @param int $height
     * @param int|float $capacity
     */
    public function __construct($height, $capacity)
    {
        $this->height = $height;
        $this->capacity = $capacity;
    }

    /**
     * Fill both hourglass bulbs.
     *
     * @param  int $height
     * @param  int|float $capacity
     * @return $this
     */
    public function fillBulbs()
    {
        // return $this->drawLine() .
        // $this->drawTopBulb() .
        // $this->drawBottomBulb() .
        // $this->drawLine();
        return $this->drawSand();
    }

    /**
     * Draw the top or bottom line of the hourglass.
     *
     * @return string
     */
    private function drawLine()
    {
        $topLine = '';
        $numLines = (2 * $this->height) + 1;

        for ($idx = 1; $idx <= $numLines; $idx++) {
            $topLine .= '_';
        }

        $topLine .= "\n";

        return $topLine;
    }

    private function drawSand()
    {
        $result = '';
        $sandCountTop = 0;
        $sandCountBottom = 0;
        $sandTopArray = $sandBottomArray = [];

        //////////TOP BULB///////////////////////
        //generate sand for each level based on the height
        for ($level = 1; $level <= $this->height; $level++) {
            //insert sand
            for ($idx = 0; $idx <= 2 * ($this->height - $level); $idx++) {
                $result .= 'x';
                $sandCountTop++;
            }

            $sandTopArray[] = $sandCountTop;

            $result .= "\n";
        }

        //////////BOTTOM BULB///////////////////////
        //generate sand for each level based on the height
        for ($level = $this->height; $level >= 1; $level--) {
            //insert sand
            for ($idx = 2 * ($this->height - $level); $idx >= 0; $idx--) {
                $result .= 'x';
                $sandCountBottom++;
            }

            $sandBottomArray[] = $sandCountBottom;

            $result .= "\n";
        }

        $hgArray = explode("\n", $result);
        var_dump($hgArray);

        $topCapacity = $this->capacity/100;
        $bottomCapacity = 100 - $topCapacity;
        $topSand = $sandCountTop * $topCapacity;
        $bottomSand = $sandCountBottom * $bottomCapacity;

        for ($top = 0,$bottom = $this->height-1; $top < $this->height,$bottom > 0; $top++,$bottom--) {
            for ($topCol = $hgArray, $botCol = 0; $topCol < $) {

                $hgArray[][] = ' ';
            }
        }

        return $result;
    }

    // /**
    //  * Draw the top bulb.
    //  *
    //  * @return string
    //  */
    // private function drawTopBulb()
    // {
    //     $topBulb = '';
    //     $sandCount = 0;

    //     //generate sand for each level based on the height
    //     for ($level = 1; $level <= $this->height; $level++) {
    //         //draw spaces
    //         for ($idx = 1; $idx < $level; $idx++) {
    //             $topBulb .= ' ';
    //         }

    //         //draw left-side line
    //         $topBulb .= '\\';

    //         //insert sand
    //         for ($idx = 0; $idx <= 2 * ($this->height - $level); $idx++) {
    //             $topBulb .= 'x';
    //             $sandCount++;
    //         }

    //         //draw right-side line
    //         $topBulb .= "/\n";
    //     }

    //     // $topBulb = array_filter(explode("\n", $topBulb));
    //     // $grains = ($capacity/100) * $sandCount;

    //     // for ($row = $height; $row >= 1; $row--) { //loop through each row
    //     // 	$space_count = 0;

    //     // 	if ($capacity % 2 != 0) { //right-side precedence
    //     // 		for ($col = sizeof($top_bulb[$row]); $col > 0; $col--) {
    //     // 			if ($space_count == $spaces) {
    //     // 				break;
    //     // 			}
    //     // 			$top_bulb[$row][$col] = '';
    //     // 			$space_count++;
    //        //  	}//for
    //     // 	}
    //     // 	else { //left-side precedence
    //        //  	for ($col = 0; $col < sizeof($top_bulb[$row]); $col++) {
    //        //  		if ($space_count == $spaces) {
    //     // 				break;
    //     // 			}
    //     // 			$top_bulb[$row][$col] = '';
    //     // 			$space_count++;
    //        //  	}//for
    //     // 	}//if-else
    //     // }//for

    //     // // return $top_bulb;
    //     // $new_top_bulb = '';

    //     // //generate sand for each level based on the height
    //     // for ($level = $height; $level >= 1; $level--) {
    //     // 	//draw spaces
    //     //     for ($idx = 1; $idx < $level; $idx++) {
    //     //         $new_top_bulb .= ' ';
    //     //     }

    //     //     //draw left-side line
    //     //     $new_top_bulb .= '\\';

    //     //     //insert sand
    //     //     for ($idx = 1; $idx <= 2 * ($height - $level); $idx++) {
    //     //         $new_top_bulb .= $top_bulb[$level][$idx];
    //     //     }

    //     //     //draw right-side line
    //     //     $new_top_bulb .= "/\n";
    //     // }

    //     // return $new_top_bulb;
    //     return $topBulb;
    // }

    // /**
    //  * Draw the bottom bulb.
    //  *
    //  * @return string
    //  */
    // private function drawBottomBulb()
    // {
    //     $bottomBulb = '';
    //     $sandCount = 0;

    //     //generate sand for each level based on the height
    //     for ($level = $this->height; $level >= 1; $level--) {
    //         //draw spaces
    //         for ($idx = 1; $idx < $level; $idx++) {
    //             $bottomBulb .= ' ';
    //         }

    //         //draw right-side line
    //         $bottomBulb .= '/';

    //         //insert sand
    //         for ($idx = 2 * ($this->height - $level); $idx >= 0; $idx--) {
    //             $bottomBulb .= 'x';
    //             $sandCount++;
    //         }

    //         // $bottomBulb .= "\n";
    //         //draw left-side line
    //         $bottomBulb .= "\\\n";
    //     }

    //     // $grains = (100 - $capacity/100) * $sandCount;

    //     return $bottomBulb;
    // }
}//class Hourglass
