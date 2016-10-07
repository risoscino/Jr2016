<?php

function printHourglass($bulbHeight, $N){
    // invalid arguments
    if($bulbHeight < 2){
        exit("First argument error. 2 <= x allowed.");
    }
    if($N > 100){
        exit("Second argument error. 0 <= y <= 100 allowed.");
    }
    
    // calculations
    $capacity = $bulbHeight * $bulbHeight; // how many 'x' each builb can hold    
    $topAmount = ceil($capacity * $N * 0.01); // number of 'x' in top bulb
    $bottomAmount = floor($capacity * (100 - $N) * 0.01); // number of 'x' in bottom bulb

    // print top end
    $numUnderscores = 2 * $bulbHeight + 1;
    for($i = 0; $i < $numUnderscores; $i++){
        echo("_");
    }
    
    echo("<br>");
    
    // print body of top bulb 
    for($i = 0; $i < $bulbHeight; $i++){ // create rows
        $numSpotsRow = $numUnderscores - 2 - 2 * $i;
        for($j = $i; $j > 0; $j--){
            echo("&nbsp");
        }
        echo("\\");        
        for($j = $numUnderscores - 2 - 2 * $i; $j > 0; $j--){ // create sand
            if($bottomAmount > 0){ // $bottomAmount, at first, is the initial number of spaces the bulb should have
                if($numSpotsRow >= $bottomAmount){ // true: mix sand with spaces
                    $numX = $numSpotsRow - $bottomAmount;
                    
                    $numXLeftSide = intval($numX / 2);
                    
                    for($k = 0; $k < $numXLeftSide; $k++){
                        echo("x");
                    }
                    for($k = 0; $k < $bottomAmount; $k++){
                        echo("&nbsp");
                    }
                    $numXRightSide = $numSpotsRow - $numXLeftSide - $bottomAmount;
                    for($k = 0; $k < $numXRightSide; $k++){
                        echo("x");
                    }
                    $bottomAmount = 0;
                    break; // done with the top bulb
                }
                else{
                   echo("&nbsp"); 
                }                
                $bottomAmount--;
            }
            else{
                echo("x");  
            }            
        }
        echo("/");
        echo("<br>");
    }
    
    // print body of bottom bulb
    for($i = 0; $i < $bulbHeight; $i++){ // create rows
        $numSpotsRow = 2 * $i + 1;
        for($j = $bulbHeight - $i - 1; $j > 0; $j--){ // !!!!!!!
            echo("&nbsp");
        }
        echo("/");        
        for($j = $numSpotsRow; $j > 0; $j--){ // create sand
            if($topAmount > 0){ // $bottomAmount, at first, is the initial number of spaces the bulb should have
                if($numSpotsRow >= $topAmount){ // true: mix sand with spaces
                    $numSpaces = $numSpotsRow - $topAmount;
                    
                    $numSpacesLeftSide = intval($numSpaces / 2);
                    
                    for($k = 0; $k < $numSpacesLeftSide; $k++){
                        echo("_");
                    }
                    for($k = 0; $k < $topAmount; $k++){
                        echo("x");
                    }
                    $numSpacesRightSide = $numSpotsRow - $numSpacesLeftSide - $topAmount;
                    for($k = 0; $k < $numSpacesRightSide; $k++){
                        echo("_");
                    }
                    $topAmount = 0;
                    break; // done with the bottom bulb
                }
                else{
                   echo("&nbsp"); 
                }                
                $topAmount--;
            }
            else{
                echo("x");  
            }            
        }
        echo("\\");
        echo("<br>");
    }
}

printHourglass(3, 71);
printHourglass(5, 52);
printHourglass(6, 75);