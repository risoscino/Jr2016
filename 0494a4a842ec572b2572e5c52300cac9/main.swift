//
//  main.swift
//  Practice Test for RI Job
//
//  Created by Matthew Connors on 9/28/16.
//  Copyright © 2016 Matt Connors. All rights reserved.
//

import Foundation

// Getting Height from user
print("How high do you want your hour glass?")

var height = Int(readLine()!)!

while (height < 2 || height > 10) {
    
    print("Height has to be from 2-10. Please try again")
    
    height = Int(readLine()!)!

}

// Getting Percent from user
print("What percentage do you want it filled?")

var topSand = Int(readLine()!)!

while (topSand < 0 || topSand > 100) {
    
    print("Percentage has to be from 0-100. Please try again")
    
    topSand = Int(readLine()!)!
    
}
// VARIABLES

var totalSand = height * height
var bottomSand = Double(100 - topSand)
var topSandPercent = Double(topSand) / 100
var bottomSandPercent = Double(bottomSand) / 100
var bottomXs = Int(Double(totalSand) * bottomSandPercent)
var topXs = Int(ceil(Double(totalSand) * topSandPercent))
var spacesOnTop = totalSand - topXs
var spacesOnBottom = totalSand - bottomXs
var counter = spacesOnTop
var space = 2 * height - 1
var remainder = spacesOnTop - space
var totalLeft = totalSand - space
var bottomRemainder = bottomXs - space
var spacesLeft = totalLeft - bottomRemainder




// Drawing the top line length = 2 * height + 1
let length = height * 2 + 1

for i in 1...length {
    print("_", terminator: "")
}

print("")

// Drawing the top half of the hour glass using  "\" distance until "/" = (2 * height - 1)


// print first line of hour glass with no spaces

for i in 1...1 {
    
    for k in 1...1 {
        
        print("\\", terminator: "")
        
    }
    
    // Print all spaces if number of spaces is greater than top line
    
    if spacesOnTop >= space {
        
        for l in 1...space {
            
            print(" ", terminator: "")
            
        }
        print("/")
        print("")
        
        
        // else print number of Xs after spaces
    } else {
        
        for l in 1...space - counter {
            
            while counter > 0 {
                
                print(" ", terminator: "")
                
                counter -= 1
            }
            
            
            print("x", terminator: "")
            
        }
        print("/")
        print("")
        
        
        
    }
    
    
    
    // print the rest of the top portion
    
    var rest = height - 1
    
    for i in 1...rest {
        
        
        
        for j in 1...i {
            
            print(" ", terminator: "")
            
        }
        for k in 1...1 {
            
            print("\\", terminator: "")
            
        }
        for l in 1...space - 2 * i {
            
            if remainder > 0 {
                print(" ", terminator: "")
                remainder -= 1
            } else {
                print("x", terminator: "")
                
            }
        }
        
        print("/")
        print("")
        
    }
    
    // mirror the top half of the hour glass
    
    for i in 1...rest {
        
        
        
        for j in 1...rest - i + 1 {
            
            print(" ", terminator: "")
            
        }
        for k in 1...1 {
            
            print("/", terminator: "")
            
        }
        for l in 0...2 * i - 2 {
            
            if spacesLeft > 0 {
                
                print(" ", terminator: "")
                spacesLeft -= 1
                
            } else {
                
                print("x", terminator: "")
                
            }
            
        }
        
        print("\\")
        print("")
        
    }
    
    // Print the last line of the hour glass with no spaces
    
    for i in 1...1 {
        
        for k in 1...1 {
            
            print("/", terminator: "")
            
        }
        for l in 1...space {
            
            if bottomXs > 0 {
                
                print("x", terminator: "")
                bottomXs -= 1
                
            } else {
                
                print("_", terminator: "")
                
            }
            
        }
        
        print("\\")
        print("")
        
    }
    
}

