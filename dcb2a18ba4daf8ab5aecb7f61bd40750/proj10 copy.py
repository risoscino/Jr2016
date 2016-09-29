    ###########################################################
    #  Computer Project #10
    #
    #  Menu
    #  Function for valid move
    #       finds valid move based on rank
    #  Function for tableau move
    #       error checking for valid move
    #  Function for foundation move
    #       error checking for valid move 
    #  Function for winning
    #  Function for initiating the game
    #  Function for displaying the game
    #  loop for menu options
    ###########################################################

import cards   # This line is required


#Rules for the game
RULES = '''
Alaska Card Game:
     Foundation: Columns are numbered 1, 2, 3, 4
                 Built up by rank and by suit from Ace to King.
                 The top card may be moved.
     Tableau: Columns are numbered 1,2,3,4,5,6,7
              Built up or down by rank and by suit.
              The top card may be moved.
              Complete or partial face-up piles may be moved.
              An empty spot may be filled with a King or a pile starting with a King.
     To win, all cards must be in the Foundation.'''

#Menu for the game
MENU = '''
Input options:
    F x y : Move card from Tableau column x to Foundation y.
    T x y c: Move pile of length c >= 1 from Tableau column x to Tableau column y.
    R: Restart the game (after shuffling)
    H: Display the menu of choices
    Q: Quit the game
'''

#Function for returning a valid move
def valid_move(c1,c2):
    '''return True if suits are the same and ranks differ by 1; c1 & c2 are cards.'''
    c1_suit = c1.suit()
    c2_suit = c2.suit()
    c1_rank = c1.rank()
    c2_rank = c2.rank()
    
    if abs(c1_rank - c2_rank) == 1:
        if c1_suit == c2_suit:
            return True
        else:
            return False
 
#Function for moving cards in the tableau       
def tableau_move(tableau,x,y,c):
    '''Move pile of length c >= 1 from Tableau column x to Tableau column y.
       Return True if successful'''
    if c > len(tableau[x]):
        print("Error: Invalid Move")
        return False
    cards = tableau[x][-c:]
    first_card = tableau[x][-c]
    if tableau[y] == [] and first_card.rank() == 13:
        tableau[y].extend(cards)
        tableau[x] = tableau[x][:-c]
        if tableau[x] and tableau[x][-1].is_face_up() == False:
            tableau[x][-1].flip_card()
        return True
    elif tableau[y]:
        end_card = tableau[y][-1]
        if valid_move(first_card, end_card) == True:
            tableau[y].extend(cards)
            tableau[x] = tableau[x][:-c]
            if tableau[x] and tableau[x][-1].is_face_up() == False:
                tableau[x][-1].flip_card()
            return True
        else:
            print("Error: Invalid Move")
    return False
 
#Function for moving cards to the foundation           
def foundation_move(tableau,foundation,x,y):
    '''Move card from Tableau x to Foundation y.
       Return True if successful'''
    if tableau[x] == []:
        print("Error: Invalid Move")
        return False
    tableau_card = tableau[x][-1]
    if foundation[y]==[] and tableau_card.rank() == 1:
        tableau[x].pop()
        foundation[y].append(tableau_card)
        if tableau[x] and tableau[x][-1].is_face_up() == False:
            tableau[x][-1].flip_card()
        return True
    elif foundation[y]:
        foundation_card = foundation[y][-1]
        if valid_move(tableau_card,foundation_card) == True:
            foundation[y].append(tableau_card)
            tableau[x].pop()
            if tableau[x] and tableau[x][-1].is_face_up() == False:
                tableau[x][-1].flip_card()
            return True
        else:
            print("Error: Invalid Move")
    return False
        
        
#Function for winning the game
def win(tableau,foundation):
    '''Return True if the game is won.
       Note that you may use the tableau or foundation or both -- your choice.'''
    if tableau[[],[],[],[],[],[],[]]:
        return True
    else:
        return False

#Function for ititating the game    
def init_game():
    '''Initialize and return the tableau, and foundation.
       - foundation is a list of 4 empty lists
       - tableau is a list of 7 lists
       - deck is shuffled and then all cards dealt to the tableau'''
    my_deck = cards.Deck()
    my_deck.shuffle()
    foundation = [[],[],[],[]] # replace with list
    tableau = [[],[],[],[],[],[],[]]    # replace with list of lists
    tableau[0] = []
    #for i in range (1):
    tableau[0].append(my_deck.deal())
    for i in range (1,7):
        for j in range(i):
            tableau[i].append(my_deck.deal())
            tableau[i][-1].flip_card()
        for j in range(5):
            tableau[i].append(my_deck.deal())
            

    
    return tableau, foundation

#Function for displaying the game    
def display_game(tableau, foundation):
    '''Display foundation with tableau below.
       Format as described in specifications.'''
    print( "===========================" )
    for lst in foundation:
        if lst != []:
            print(lst[-1], end = '')
        else:
            print('   ', end = '')
    print()
    print("---------------------------")

    for i in range(11):
        for j in range(7):
            if len(tableau[j]) > i:
                print("{:<3}".format(str(tableau[j][i])), end = ' ')
            else:
                print('    ', end = '')
        print()
            
#Starting the game   
print(RULES)      
tableau,foundation = init_game()
display_game(tableau, foundation)

print(MENU)
choice = input("Enter a choice: ")

#loop for the menu
while choice[0].lower() != 'q':
    choice_lst = choice.split(" ")
    if choice_lst[0].lower()=="f":
        if len(choice_lst) == 3 and choice_lst[1].isdigit() and \
        choice_lst[2].isdigit():
            x = int(choice_lst[1])-1
            y = int(choice_lst[2])-1
            foundation_move(tableau,foundation,x,y)
        elif len(choice_lst) != 3:
            print("Incorrect number of arguments")
        else:
            print("Incorrect type of arguments")
        
    elif choice_lst[0].lower()=="t":
        if len(choice_lst) == 4 and choice_lst[1].isdigit() and \
        choice_lst[2].isdigit() and choice_lst[3].isdigit():
            x = int(choice_lst[1])-1
            y = int(choice_lst[2])-1
            c = int(choice_lst[3])
            tableau_move(tableau,x,y,c)
        elif len(choice_lst) != 4:
            print("Incorrect number of arguments")
        else:
            print("Incorrect type of arguments")
            
        
    elif choice[0] == 'r':
       tableau,foundation = init_game()
  
  
    elif choice[0] == 'h':
        print(MENU)
        
    else:
        print("Error: Incorrect Command")
    
            
    display_game(tableau, foundation)
    choice = input("Enter a choice: ")
