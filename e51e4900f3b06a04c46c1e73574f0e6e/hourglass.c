#include <stdio.h>
#include <stdlib.h>

//Define limits
#define BULBMIN 2
#define PCTMIN 0
#define PCTMAX 100

int main(void){
  //initialize variables
  int height, row, spaces, stars, percentage, starCount;
  while(1){
      //ask user for inputs
      printf("Please input bulb height and percentage of fill with a space: ");
      scanf("%d%d", &height, &percentage);
      //input validations
      if(height < 2){
          printf("Please enter bulb height greater than or equal to 2 \n");
      }else if(percentage < PCTMIN || percentage > PCTMAX){
          printf("Please enter percentage between 0 to 100 \n");
      }else{
          // calculate number of stars for each bulb
          int totalSpaces = (height + 1)*height;
          int totalDashes = height * 2;
          int totalStars = totalSpaces - totalDashes;
          int starsForUpper = totalStars - ((totalStars * percentage)/100);
          int starsForLower = totalStars - starsForUpper;
          //logic for upper bulb
          starCount = 0;
          for (row = 0; row < height; row ++){
              for (spaces = 0; spaces < row; spaces ++)
                  printf(" ");
              for (stars = 0; stars < 2 * (height - row); stars ++){
                  if(stars == 0){
                      printf("\\"); //make upper left corner stars with backward slash
                  }else if(stars == (2 * (height - row))-1){
                      printf("/"); // make upper right corner stars with slash
                  }else{//logic for star fill in upper bulb
                      if(starCount < starsForUpper){
                          printf(" ");
                      }else{
                          printf("*");
                      }
                      starCount ++;
                  }
              }
              printf("\n");
          }
          //logic for lowe bulb
          starCount = 0;
          for (row = 0; row < height; row ++){
              for (spaces = height-row; spaces > 1; spaces --)
                  printf(" ");
              for (stars = 0; stars < 2 * (row+1); stars ++){
                  if(stars == 0){
                      printf("/");// make lower left corner stars with slash
                  }else if(stars == (2 * (row+1))-1){
                      printf("\\");//make lower right corner stars with backward slash
                  }else{//logic for star fill in lower bulb
                      if(starCount < starsForLower){
                          printf(" ");
                      }else{
                          printf("*");
                      }
                      starCount ++;
                  }
              }
              printf("\n");
          }
          break;
      }
      return 0;
  }
}
