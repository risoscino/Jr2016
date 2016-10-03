import java.util.Scanner;
import java.util.*;
//java
public class Time {
	
	public static void main (String[] args){
		
		System.out.println("Input number greater than 1");
		Scanner keyboard = new Scanner(System.in);
		double Height = keyboard.nextInt();
			
		System.out.println("Input number between 0-100");
		Scanner keyboardw = new Scanner(System.in);
		double Time = keyboardw.nextInt();  
		
		double Area = Height*Height;
		int Count = 0 ;
		int Count2= 0 ;
		int Count3= 0 ;
		double TopStars = (Time/100)*Area;
		double BottomStars = Area-TopStars;
		
		for (double i= Height*2-1; i>0; i -= 2)
		{
			StringBuilder out = new StringBuilder();
			int Stars = 0;
			if (Count==0){
				System.out.print(" ");
					for(int T=1; T<Height*2+2;T++)
					{					
						System.out.print("_");
					}
					System.out.println("");
			}
			
			for (int j=0; j < (Height- i / 2); j++)
				{
				System.out.print(" ");
				}
			
			System.out.print("\\");
			
			for (int j=0; j<=i-1; j++)
				{
				if(TopStars>Count)
				{
					out.append(" ");
					Count++;
				}else{
					Stars++;
				}
				}

			if(Stars ==0){
			}else if (Stars==1){
				out.insert(0, "*");
			}else {
				int left = Stars / 2;
				int right = (Stars/2) + (Stars%2);
				for(int ii = 0; ii < left; ii++){
					out.insert(0, "*");
				}
				for(int iii = 0; iii < right; iii++){
					out.append("*");
				} 
			}
			System.out.println(out.toString()+"/");
		}
		
		
		for (int i=1; i<Height*2; i += 2)
		{
			for (int j=0; j < (Height- i / 2); j++)
			{
				System.out.print(" ");
			}
			StringBuilder out = new StringBuilder();
			int blanks = 0;
			String space = " ";
			
			System.out.print("/");
			Count3++;
			for (int j=0; j<i; j++)
			{
				if(BottomStars>Count2){
					if(Height==Count3){
						space = "_";
					}else{
						space = " ";
					}
					blanks++;
					Count2=Count2+1;
				}else{
					out.append("*");
				}
			}
				
			if (Count2==0){
				System.out.print(" ");
				for(int T=1; T<Height*2+2;T++)
				{
					System.out.print("_");
				}
				System.out.println("");
			}
			if(blanks == 0){
			}else if (blanks == 1){
				out.insert(0,space);
			}else{
				int left = blanks / 2;
				int right = (blanks/2) + (blanks%2);
				for(int ii = 0; ii < left; ii++){
					out.insert(0, space);
				}
				for(int iii = 0; iii < right; iii++)
				{
					out.append(space);
				}
			}
			System.out.println(out.toString()+"\\");
		}
	}			
}
