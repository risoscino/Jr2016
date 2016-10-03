/*
ex: (3 71%): draw(3, 71)
output showed in console
*/
function draw(height, percentage){
    //check input
    if( height < 2 || percentage < 0 || percentage > 100) {
        console.log('invaild input');
        return;
    }
    
    var lowerSpace = Math.ceil(Math.pow(height, 2) * percentage / 100);
    var upperSpace = Math.pow(height, 2) - lowerSpace;
    var temp = "";
    var l, s;
    
    //print first line
    temp = "_";
    for(var i = 0; i < height; i++){
        temp = temp+ '__';
    }
    console.log(temp);
    
    //print the rest
    for(var i = 0; i < 2 * height; i++){
        temp = '';
        
        //pre space
        for(var j = 0; j < height - Math.ceil(Math.abs(height - 0.5 - i)); j++){
            temp = temp + ' ';
        }  
        
        //upper part
        if(i < height){
            temp = temp + '\\';
            l = 2 * (height - i) - 1;
            s = upperSpace>l? l : upperSpace;
            upperSpace = upperSpace-l<=0? 0 : upperSpace - l;
            temp = temp + fillContents(l-s, 'x', s, ' ', true) + '/';
        }
        //lower part
        else {
            temp = temp + '/';
            l = 2 * (i - height) + 1;
            s = lowerSpace>l? l : lowerSpace;
            lowerSpace = lowerSpace-l<=0? 0 : lowerSpace - l;
            
            //last line
            if(i === 2 * height - 1){
                temp = temp + fillContents(s, '_', l-s, 'x', false) + '\\';    
            }
            else{
                temp = temp + fillContents(s, ' ', l-s, 'x', false) + '\\';    
            }
        }
        console.log(temp);
    }
    
    //fill each line's content
    function fillContents(r, rIcon, s, sIcon, upper){
        var res='';
        var pre, post;
        
        if(upper){
            pre = Math.floor(r/2);
            post = r - pre;
        }
        else{
            pre = Math.ceil(r/2);
            post = r - pre;
        }
        
        while(pre > 0){
            res = res + rIcon;
            pre--;
        }
        while(s > 0){
            res = res + sIcon;
            s--;
        }
        while(post > 0){
            res = res + rIcon;
            post--;
        }
        return res;
    }
}

//run example test cases
draw(3, 71);
draw(5, 52);
draw(6, 75);
