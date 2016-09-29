<?php
  /**
    * hourglass.php display a hourglass base on the user input
    * run file as command line in terminal php hourglass.php
    * @author Wai Chan waichan401@gmail.com
    * 
    */


   //get user input
    $response ="";
    while($response=="" || validateInput($response)==false){
  
    echo "\n\nPlease enter two number seperate by a space. First number is a greater than 1 integer that represents the height of the bulbs, second number is a percentage (0 - 100) of the hourglass' capacity. (eg. 3 10%) \n";
        echo "Input: ";
        $stdin = fopen('php://stdin', 'r');
        //strip any unnecessarily character 
        $response = str_replace('%','',fgets($stdin,10));
        $response = mysql_escape_string( rtrim($response)); 
    }
    echo "Output: \n" ;
    $numbers = explode(" ", $response);
    drawHourGlass(intval($numbers[0]),intval($numbers[1]));
    
    //debug info
    //drawHourGlass(2,10);
    //drawHourGlass(3,71);
    //drawHourGlass(5,52);
    //drawHourGlass(6,75);

    /**
    * Validate the user input if it is not validate print why and return false
    *
    * @param string $userInput this is the height of hourglass
    * @return bool true when it is valid other wise return false
    */
    function validateInput($userInput){
       $isValid = true;
       $numbers = explode(" ", $userInput);
       //need excatly two numbers    
       if(count($numbers)!=2){
           echo "Invalid input";
           $isValid =false;
       }
       //make sure the input are numeric
       if($isValid){
           foreach ($numbers as $element) {
               
                if (!is_numeric($element)) {
                    echo "'{$element}' is NOT a number";
                    $isValid =false;
                }
           }
       }
       //make sure the numbers are valid
       if($numbers[0]<2 || $numbers[1]<0 || $numbers[1]>100){
           echo "\nFirst number must be greater than 1. Second number must be between 0 - 100 ";
           $isValid =false;
       }
       return $isValid;
    }


    /**
    * Draw the Hourglass on the screen based on the height an percentage
    *
    * @param int $height this is the height of hourglass
    * @param int $percentage this is the percentage of sand in the top blub of the hourglass
    */

    function drawHourGlass($height,$percentage){
        
        $topBulb = array();
        $bottomBulb = array();

        $numOfColumn = $height*2 + 1;
        $halfCapacity = $height * $height;
       // $totalCapacity = $halfCapacity*2;
        $numberOfSpaceUsedTop = ceil($halfCapacity * ($percentage/100)) ;
        $numberOfSpaceUsedBottom = $halfCapacity - $numberOfSpaceUsedTop;
        
        
        //debug info
        //echo "Total Capacity " . $totalCapacity ;
        //echo "\nNumber Of Space Used for Top Blub " . $numberOfSpaceUsedTop ;
        //echo "\nNumber Of Space Used for Bottom Blub " . $numberOfSpaceUsedBottom ."\n";
                
      
        //calculate sand placement for top bulb
        for($i=0;$i<$height;$i++){
            //number of character can be use in the row
            $numberOfXs = (2*$i)+1;
            
            //where the first non space character should begin
            $startCol =(( $numOfColumn - $numberOfXs )/2)-1;
            
            $numberOfSpaceUsedrow = $numberOfSpaceUsedTop >= $numberOfXs ? $numberOfXs :   $numberOfSpaceUsedTop;
            $numberOfSpaceUnused = $numberOfXs - $numberOfSpaceUsedrow;
            
            //find where the space should start in the row
            $startPoint=0;
            if(($numberOfXs-$numberOfSpaceUnused)%2==0&& ( ($numberOfXs - $numberOfSpaceUnused)/2) >1 ){
                $startPoint =( ($numberOfXs - $numberOfSpaceUnused)/2)+1;
            }else{
                $startPoint =(($numberOfXs - $numberOfSpaceUnused)/2);
            }

            $numberOfSpaceUsedTop = $numberOfSpaceUsedTop - $numberOfXs;

            $row = "";
            $found = 0;
            
            //set temperory character for each column of the blub
            for($j=0; $j< $numOfColumn ; $j++){
                if($j<$startCol||($j>$startCol && $numberOfSpaceUnused<0&& $j < $numOfColumn - $startCol-1)){
                    $row.=" ";
                }else if($startCol == $j){
                    $row.="R";
                }else if($j == $numOfColumn - $startCol-1){
                    $row.="L";
                }else if($found== 1 && $numberOfSpaceUnused>0){
                    $row.="*";
                    $numberOfSpaceUnused = $numberOfSpaceUnused -1;
                }else if($j < $numOfColumn - $startCol-1){
                    if($found== 0&& $numberOfSpaceUnused>0&& $j >$startPoint  ){
                        $numberOfSpaceUnused = $numberOfSpaceUnused -1;
                        $row.="*";
                        $found = 1;
                    }else {
                        $row.="X";
                    }
                }
            }
            
            $numberOfSpaceUnused =0;
            //echo "\n".$row;
            
            //replace the temp character with the corret ones and store it
            $from = array('L', 'R',"*"); 
            $to = array( '/', '\\'," "); 
           
            $topBulb[$i] = str_replace($from,$to,$row);
          
            //just reverse the glass for bottom bulb
            if($i==$height-1){
                $from = array('L', 'R', 'X','*'); 
                $to = array( '/', '\\'  ,'_','X');
                $bottomBulb[$i] = strrev(str_replace($from,$to,$row) );

            }else{
                $from = array('R', 'L', 'X','*'); 
                $to = array( '/', '\\'  , ' ','X'); 
                $bottomBulb[$i] = str_replace($from,$to,$row);

            }
        }
        
        //Draw top bulb
        $firstrow = "\n";
        for($i=0;$i<$numOfColumn;$i++){
            $firstrow.="_"; 
        }
        echo $firstrow;
        for($k=$height-1;$k>-1;$k--){
            echo "\n".$topBulb[$k] ; 
        }
        //Draw bottom bulb

         for($l=0;$l<$height;$l++){
            echo "\n".$bottomBulb[$l] ; 
        }
        echo "\n";

    }
?>
