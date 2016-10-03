<?php
/**
 * hourglass.php display an hourglass based on the user's input
 * run file as command line in terminal php hourglass.php
 * @author Christopher J. Lafond
 *
 */


//get user input
$input= "";
while ($input== "" || validateInput($input) == false) {

    echo "\n\nPlease enter two numbers separated by a space.";
    echo "\nInput: ";
    $stdin= fopen('php://stdin', 'r');
    $input= str_replace('%', '', fgets($stdin, 10));
    $input= addslashes(rtrim($input));

}

echo "\nOutput: ";
$numbers = explode(" ", $input);
drawHourGlass(intval($numbers[0]), intval($numbers[1]));

/**
 * Validate the user input if it is not validate print why and return false
 *
 * @param string $userInput this is the height of hourglass
 * @return bool true when it is valid other wise return false
 */
function validateInput($userInput)
{
    $isValid = true;
    $numbers = explode(" ", $userInput);
    //need excatly two numbers
    if (count($numbers) != 2) {
        echo "Invalid input";
        $isValid = false;
    }
    //make sure the input are numeric
    if ($isValid) {
        foreach ($numbers as $element) {

            if (!is_numeric($element)) {
                echo "'{$element}' is NOT a number";
                $isValid = false;
            }
        }
    }
    //make sure the numbers are valid
    if ($numbers[0] < 2 || $numbers[1] < 0 || $numbers[1] > 100)
    {
        echo "\nFirst number must be greater than 1. Second number must be between 0 - 100 ";
        $isValid = false;
    }

    return $isValid;
}


/**
 * Draw the Hourglass on the screen based on the height and the percentage
 *
 * @param int $height this is the height of hourglass
 * @param int $percentage this is the percentage of sand in the top blub of the hourglass
 */

function drawHourGlass($height, $percentage)
{
    $topBulb    = array();
    $bottomBulb = array();

    $numOfColumns      = $height * 2 + 1;
    $volume            = $height * $height;
    $topVolume         = ceil($volume * ($percentage / 100));
    $bottomVolume      = $volume - $topVolume;

    //calculate the sand placement for top bulb
    for ($i = 0; $i < $height; $i++) {
        //number of character can be use in the row
        $numberOfXs = (2 * $i) + 1;

        //where the first non space character should begin
        $startCol = (($numOfColumns- $numberOfXs) / 2) - 1;

        $volumeRow = $topVolume>= $numberOfXs ? $numberOfXs : $topVolume;
        $unusedVolume  = $numberOfXs - $volumeRow;

        //find where the space should start in the row
        $startPoint = 0;
        if (($numberOfXs - $unusedVolume) % 2 == 0 && (($numberOfXs - $unusedVolume) / 2) > 1)
        {
            $startPoint = (($numberOfXs - $unusedVolume) / 2) + 1;
        }

        else
        {
            $startPoint = (($numberOfXs - $unusedVolume) / 2);
        }

        $topVolume= $topVolume - $numberOfXs;

        $row   = "";
        $found = 0;

        //set temporary character for each column of the blub
        for ($j = 0; $j < $numOfColumns; $j++) {
            if ($j < $startCol || ($j > $startCol && $unusedVolume < 0 && $j < $numOfColumns- $startCol - 1))
            {
                $row .= " ";
            }

            else if ($startCol == $j)
            {
                $row .= "R";
            }

            else if ($j == $numOfColumns- $startCol - 1)
            {
                $row .= "L";
            }

            else if ($found == 1 && $unusedVolume > 0)
            {
                $row .= "*";
                $unusedVolume = $unusedVolume - 1;
            }

            else if ($j < $numOfColumns- $startCol - 1)
            {
                if ($found == 0 && $unusedVolume > 0 && $j > $startPoint)
                {
                    $unusedVolume = $unusedVolume - 1;
                    $row .= "*";
                    $found = 1;
                }
                else
                {
                    $row .= "X";
                }
            }
        }

        $unusedVolume = 0;
        //echo "\n".$row;

        //replace the temp character with the correct ones and store it
        $from = array(
            'L',
            'R',
            "*"
        );
        $to   = array(
            '/',
            '\\',
            " "
        );

        $topBulb[$i] = str_replace($from, $to, $row);

        //just reverse the glass for bottom bulb
        if ($i == $height - 1) {
            $from           = array(
                'L',
                'R',
                'X',
                '*'
            );
            $to             = array(
                '/',
                '\\',
                '_',
                'X'
            );
            $bottomBulb[$i] = strrev(str_replace($from, $to, $row));
        }

        else
        {
            $from           = array(
                'R',
                'L',
                'X',
                '*'
            );
            $to             = array(
                '/',
                '\\',
                ' ',
                'X'
            );
            $bottomBulb[$i] = str_replace($from, $to, $row);
        }
    }

    //Draw top bulb
    $firstrow = "\n";
    for ($i = 0; $i < $numOfColumns; $i++) {
        $firstrow .= "_";
    }
    echo $firstrow;
    for ($k = $height - 1; $k > -1; $k--) {
        echo "\n" . $topBulb[$k];
    }
    //Draw bottom bulb

    for ($l = 0; $l < $height; $l++) {
        echo "\n" . $bottomBulb[$l];
    }
    echo "\n";

}

/* ~Test Cases~
* drawHourGlass(3,71);
* Expected Output:
*   Input: 3 71% 
    Output: 
    _______
    \x  xx/ 
     \xxx/
      \x/ 
      / \ 
     /   \ 
    /__xx_\
    
* drawHourGlass(5,52);
* Expected Output:
*  Input: 5 52%
   Output:
    ___________ 
    \         / 
     \xx   xx/ 
      \xxxxx/ 
       \xxx/ 
        \x/ 
        / \ 
       /   \ 
      /     \ 
     /  xxx  \ 
    /xxxxxxxxx\
    
* drawHourGlass(6,75);
* Expected Output:
*   Input: 6 75%
    Output:
    _____________ 
    \x         x/ 
     \xxxxxxxxx/ 
      \xxxxxxx/ 
       \xxxxx/ 
        \xxx/ 
         \x/ 
         / \ 
        /   \ 
       /     \ 
      /       \ 
     /         \
    /_xxxxxxxxx_\
*/
?>
