
function displayHourGlass() {
	var height = document.getElementById('height').value;
	var percentage = document.getElementById('percentage').value;
	if(height<2 || height == "") {
		console.log("Please enter height greater than 1")
	}
	else if(percentage >100 || percentage == "") {
		console.log("Please enter percentage below or equal to 100")
	}
	else {
	fillBulb(height,percentage);
	}
}

function createBulb(height) {
	var arr_top = [], arr_bottom = [];
	for(i=height;i>=1;i--) {
		var str = [];
		for(j=1;j<=(2*height+1);j++){
			if(i==j)
				str[j] = "/";
			else if(j>i && j<=(2*height+1-i))
				str[j] = "_";
			else if(j == 2*height+2-i)
				str[j] = "\\";
			else
				str[j] ="&nbsp&nbsp";
		}
		arr_bottom.push(str.join(""));
		arr_bottom.push("<br>");
		arr_top.unshift(str.reverse().join(""));
		arr_top.unshift("<br>");	
	}	
		return {
			top: arr_top.join(""),
			bottom: arr_bottom.join("")
		}
}

function fillBulb(height,percentage) {
	var totalstars, upperstars,bottomstars,bulb,top_bulb,bottom_bulb,full_bulb, top_border = "";
	 
	 totalstars = height*height;
	 upperstars = Math.round(percentage*totalstars/100);
	 bottomstars = totalstars-upperstars;

	 for(i=1;i<=(2*height+1);i++) {
	 	top_border += "_";
	 }
	 
	 bulb = createBulb(height);
	 top_bulb = bulb.top;
	 bottom_bulb = bulb.bottom;
	 
	 full_bulb = "";
	
	top_bulb = setBulb(top_bulb,upperstars);
	bottom_bulb = setBulb(bottom_bulb,bottomstars);
	bottom_bulb = setBorder(bottom_bulb);		
	full_bulb = top_border + "" + top_bulb +"<br>"+ bottom_bulb;
		
	document.getElementById('hourglass').innerHTML += full_bulb;
}

function setBorder(bulb) {
	var start = bulb.lastIndexOf("/");
	var end = bulb.lastIndexOf("\\");
	var spaces= bulb.substring(start,end);
	var under_scores = spaces.replace(/&nbsp&nbsp/gi,"_");
	bulb = bulb.substring(0,start) + under_scores + bulb.substr(end);
	return bulb;
}

function reverseString(string) {
	return string.split("").reverse().join("");
}

function replaceString(string,stars) {
	replaced = string.replace(/_/gi,function myFunction(str) {
			if(stars!=0) {
				stars--;
				return "*";
			}
			else {
				return "psbn&psbn&";
			}
			
		});
	return replaced;
}

function setBulb(string,stars) {
	string = reverseString(string);
	string = replaceString(string,stars);
	string = reverseString(string);
	return string;
}



