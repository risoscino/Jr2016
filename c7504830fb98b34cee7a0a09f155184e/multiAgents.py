# multiAgents.py
# --------------
# Licensing Information:  You are free to use or extend these projects for
# educational purposes provided that (1) you do not distribute or publish
# solutions, (2) you retain this notice, and (3) you provide clear
# attribution to UC Berkeley, including a link to http://ai.berkeley.edu.
# 
# Attribution Information: The Pacman AI projects were developed at UC Berkeley.
# The core projects and autograders were primarily created by John DeNero
# (denero@cs.berkeley.edu) and Dan Klein (klein@cs.berkeley.edu).
# Student side autograding was added by Brad Miller, Nick Hay, and
# Pieter Abbeel (pabbeel@cs.berkeley.edu).
#
# Jay Luther and Michael Zawislak


from util import manhattanDistance
from game import Directions
import random, util
import sys

from game import Agent

class ReflexAgent(Agent):
    """
      A reflex agent chooses an action at each choice point by examining
      its alternatives via a state evaluation function.

      The code below is provided as a guide.  You are welcome to change
      it in any way you see fit, so long as you don't touch our method
      headers.
    """
    def __init__(self):
        self.tabu_list = []

    def getAction(self, gameState):
        """
        You do not need to change this method, but you're welcome to.

        getAction chooses among the best options according to the evaluation function.

        Just like in the previous project, getAction takes a GameState and returns
        some Directions.X for some X in the set {North, South, West, East, Stop}
        """
        # Collect legal moves and successor states
        legalMoves = gameState.getLegalActions()

        # Choose one of the best actions
        scores = [self.evaluationFunction(gameState, action) for action in legalMoves]
        bestScore = max(scores)
        bestIndices = [index for index in range(len(scores)) if scores[index] == bestScore]
        chosenIndex = random.choice(bestIndices) # Pick randomly among the best

	self.tabu_list.insert(0, gameState.generatePacmanSuccessor(legalMoves[chosenIndex]).getPacmanPosition())
        if len(self.tabu_list) > 5:
            self.tabu_list.pop()
        "Add more of your code here if you want to"

        return legalMoves[chosenIndex]

    def evaluationFunction(self, currentGameState, action):
        """
        Design a better evaluation function here.

        The evaluation function takes in the current and proposed successor
        GameStates (pacman.py) and returns a number, where higher numbers are better.

        The code below extracts some useful information from the state, like the
        remaining food (newFood) and Pacman position after moving (newPos).
        newScaredTimes holds the number of moves that each ghost will remain
        scared because of Pacman having eaten a power pellet.

        Print out these variables to see what you're getting, then combine them
        to create a masterful evaluation function.
        """
        # Useful information you can extract from a GameState (pacman.py)
        successorGameState = currentGameState.generatePacmanSuccessor(action)
        x,y = successorGameState.getPacmanPosition()
        newFood = successorGameState.getFood()
        newGhostStates = successorGameState.getGhostStates()
        newScaredTimes = [ghostState.scaredTimer for ghostState in newGhostStates]


        score = successorGameState.getScore()
        ghostPositions = successorGameState.getGhostPositions()
        oldFoodDist = []
        foodDist = []
        total = 0
        index = 0
        for s in ghostPositions:
            xy1 = x,y
            xy2 = s
            scaredTime = newScaredTimes[index]
            index = index + 1
            dist = abs(xy1[0] - xy2[0]) + abs(xy1[1] - xy2[1])
            if dist <= 1 and scaredTime == 0:
                score = score - 500

        for a, row in enumerate(newFood):
            for b, cell in enumerate(row):
                if newFood[a][b]:
                    xy = a,b
                    xyold = currentGameState.getPacmanPosition()
                    xynew = x,y
                    distold = abs(xy[0] - xyold[0]) + abs(xy[1] - xyold[1])
                    distnew = abs(xy[0] - xynew[0]) + abs(xy[1] - xynew[1])
                    oldFoodDist.append(distold)
                    foodDist.append(distnew)
        if oldFoodDist != []:
            oldMin = min(oldFoodDist)
            newMin = foodDist[oldFoodDist.index(oldMin)]
            if newMin < oldMin:
                score = score + 20
        
                    
        return score

def scoreEvaluationFunction(currentGameState):
    """
      This default evaluation function just returns the score of the state.
      The score is the same one displayed in the Pacman GUI.

      This evaluation function is meant for use with adversarial search agents
      (not reflex agents).
    """
    return currentGameState.getScore()

class MultiAgentSearchAgent(Agent):
    """
      This class provides some common elements to all of your
      multi-agent searchers.  Any methods defined here will be available
      to the MinimaxPacmanAgent, AlphaBetaPacmanAgent & ExpectimaxPacmanAgent.

      You *do not* need to make any changes here, but you can if you want to
      add functionality to all your adversarial search agents.  Please do not
      remove anything, however.

      Note: this is an abstract class: one that should not be instantiated.  It's
      only partially specified, and designed to be extended.  Agent (game.py)
      is another abstract class.
    """

    def __init__(self, evalFn = 'scoreEvaluationFunction', depth = '2'):
        self.index = 0 # Pacman is always agent index 0
        self.evaluationFunction = util.lookup(evalFn, globals())
        self.depth = int(depth)
	self.pacmanIndex = 0

class MinimaxAgent(MultiAgentSearchAgent):
    """
      Your minimax agent (question 2)
    """

    def getAction(self, gameState):
        """
          Returns the minimax action from the current gameState using self.depth
          and self.evaluationFunction.

          Here are some method calls that might be useful when implementing minimax.
        """
        #gameState.getLegalActions(agentIndex):
          #  Returns a list of legal actions for an agent
          #  agentIndex=0 means Pacman, ghosts are >= 1

        #gameState.generateSuccessor(agentIndex, action):
          #  Returns the successor game state after an agent takes an action

        #gameState.getNumAgents():
          #  Returns the total number of agents in the game

        #gameState.isWin():
          #  Returns whether or not the game state is a winning state

        #gameState.isLose():
          #  Returns whether or not the game state is a losing state
          
        score,move = self.maxPlay(gameState,self.depth)
        return move


    def minPlay(self,gameState,depth,agent):
        """
            Recursively find minimum value for the ghosts
        """
        worstScore = 99999
        worstMove = ""
        numAgents = gameState.getNumAgents()
        actions = gameState.getLegalActions(agent)
        for a in actions:
            currentState = gameState.generateSuccessor(agent, a)
            if currentState.isLose() or currentState.isWin():
                score = self.evaluationFunction(currentState)
                move = a
            elif (depth == 1 and agent + 1 == numAgents):
                score,move = self.minScore(gameState,agent)
            else:
                if agent + 1 == numAgents:
                    score,move = self.maxPlay(currentState,depth - 1)
                else:
                    score,move = self.minPlay(currentState,depth,agent + 1)
            if score < worstScore :
                worstScore = score
                worstMove = a
                
        
        
        return worstScore,worstMove


    def maxPlay(self,gameState,depth):
        bestScore = -99999
        bestMove = ""
        actions = gameState.getLegalActions(0)
        for a in actions:
            currentState = gameState.generateSuccessor(0, a)
            if currentState.isWin() or currentState.isLose():
                score = self.evaluationFunction(currentState)
                move = a
            else:
                score,move = self.minPlay(currentState,depth,1)
            if score > bestScore:
                bestScore = score
                bestMove = a
        
        return bestScore,bestMove

                
    def minScore(self,gameState,agent):
        worstScore = 99999
        worstMove = ""
        actions = gameState.getLegalActions(agent)
        for a in actions:
            currentState = gameState.generateSuccessor(agent, a)
            score = self.evaluationFunction(currentState)
            if score < worstScore:
                worstScore = score
                worstMove = a


        return worstScore,worstMove
        
    
        

class AlphaBetaAgent(MultiAgentSearchAgent):
    """
      Your minimax agent with alpha-beta pruning (question 3)
    """

    def getAction(self, gameState):
        """
          Returns the minimax action using self.depth and self.evaluationFunction
        """
        score,move = self.maxABPlay(gameState,self.depth,-99999,99999)
        return move
        
##        curDepth = 0
##        currentAgentIndex = 0
##        alpha = -1*float("inf")
##        beta = float("inf")
##        val = self.value(gameState, currentAgentIndex, curDepth, alpha, beta)
##        print "Returning %s" % str(val)
##        return val[0]
##		
##    def value(self, gameState, currentAgentIndex, curDepth, alpha, beta): 
##        if currentAgentIndex >= gameState.getNumAgents():
##            currentAgentIndex = 0
##            curDepth += 1
##
##        if curDepth == self.depth or gameState.isWin() or gameState.isLose():
##            return self.evaluationFunction(gameState)
##
##        if currentAgentIndex == self.pacmanIndex:
##            return self.maxValue(gameState, currentAgentIndex, curDepth, alpha, beta)
##        else:
##            return self.minValue(gameState, currentAgentIndex, curDepth, alpha, beta)
##        
##    def minValue(self, gameState, currentAgentIndex, curDepth, alpha, beta):
##        v = ("unknown", float("inf"))
##        
##        if not gameState.getLegalActions(currentAgentIndex):
##            return self.evaluationFunction(gameState)
##
##        for action in gameState.getLegalActions(currentAgentIndex):
##            if action == "Stop":
##                continue
##            
##            retVal = self.value(gameState.generateSuccessor(currentAgentIndex, action), currentAgentIndex + 1, curDepth, alpha, beta)
##            if type(retVal) is tuple:
##                retVal = retVal[1] 
##
##            vNew = min(v[1], retVal)
##
##            if vNew is not v[1]:
##                v = (action, vNew) 
##            
##            if v[1] <= alpha:
##                #print "Pruning with '%s' from min since alpha is %2.2f" % (str(v), alpha)
##                return v
##            
##            beta = min(beta, v[1])
##            #print "Setting beta to %2.2f" % beta
##        
##        #print "Returning minValue: '%s' for agent %d" % (str(v), currentAgentIndex)
##        return v
##
##    def maxValue(self, gameState, currentAgentIndex, curDepth, alpha, beta):
##        v = ("unknown", -1*float("inf"))
##        
##        if not gameState.getLegalActions(currentAgentIndex):
##            return self.evaluationFunction(gameState)
##
##        for action in gameState.getLegalActions(currentAgentIndex):
##            if action == "Stop":
##                continue
##            
##            retVal = self.value(gameState.generateSuccessor(currentAgentIndex, action), currentAgentIndex + 1, curDepth, alpha, beta)
##            if type(retVal) is tuple:
##                retVal = retVal[1] 
##
##            vNew = max(v[1], retVal)
##
##            if vNew is not v[1]:
##                v = (action, vNew) 
##            
##            if v[1] >= beta:
##                #print "Pruning with '%s' from min since beta is %2.2f" % (str(v), beta)
##                return v
##
##            alpha = max(alpha, v[1])
            #print "Setting alpha to %2.2f" % alpha

        #print "Returning maxValue: '%s' for agent %d" % (str(v), currentAgentIndex)
##        return v
		
	# def value(self, gameState, depth, alpha, beta):
        # if depth == self.depth * gameState.getNumAgents() or gameState.isWin() or gameState.isLose():
            # return (None, self.evaluationFunction(gameState))
        # if depth % gameState.getNumAgents() == 0:
            # # pacman
            # return self.maxFunc(gameState, depth, alpha, beta)
        # else:
            # # ghosts
            # return self.minFunc(gameState, depth, alpha, beta)

    # def minFunc(self, gameState, depth, alpha, beta):
        # actions = gameState.getLegalActions(depth % gameState.getNumAgents())
        # if len(actions) == 0:
            # return (None, self.evaluationFunction(gameState))

        # min_val = (None, float("inf"))
        # for action in actions:
            # succ = gameState.generateSuccessor(depth % gameState.getNumAgents(), action)
            # res = self.value(succ, depth+1, alpha, beta)
            # if res[1] < min_val[1]:
                # min_val = (action, res[1])
            # if min_val[1] < alpha:
                # return min_val
            # beta = min(beta, min_val[1])
        # return min_val

    # def maxFunc(self, gameState, depth, alpha, beta):
        # actions = gameState.getLegalActions(0)
        # if len(actions) == 0:
            # return (None, self.evaluationFunction(gameState))

        # max_val = (None, -float("inf"))
        # for action in actions:
            # succ = gameState.generateSuccessor(0, action)
            # res = self.value(succ, depth+1, alpha, beta)
            # if res[1] > max_val[1]:
                # max_val = (action, res[1])
            # if max_val[1] > beta:
                # return max_val
            # alpha = max(alpha, max_val[1])
        # return max_val
		
    def minABPlay(self,gameState,depth,agent,alpha,beta):
        """
            Recursively find minimum value for the ghosts
        """
        worstScore = 99999
        worstMove = ""
        numAgents = gameState.getNumAgents()
        actions = gameState.getLegalActions(agent)
        
        for a in actions:
            currentState = gameState.generateSuccessor(agent, a)
            if currentState.isLose() or currentState.isWin():
                score = self.evaluationFunction(currentState)
                move = a
            elif (depth == 1 and (agent + 1) == numAgents):
                score,move = self.minScore(gameState,agent)
            else:
                if worstScore < beta:
                    beta = worstScore;
                if (agent + 1) == numAgents:
                    score,move = self.maxABPlay(currentState,depth - 1,alpha,beta)   
                else:
                    score,move = self.minABPlay(currentState,depth,agent + 1,alpha,beta)
            
            if score < alpha:
                return score,a
            if score < worstScore :
                worstScore = score
                worstMove = a
        return worstScore,worstMove

    def maxABPlay(self,gameState,depth,alpha,beta):
        bestScore = -99999
        bestMove = ""
        actions = gameState.getLegalActions(0)
        for a in actions:
            currentState = gameState.generateSuccessor(0, a)
            if currentState.isWin() or currentState.isLose():
                score = self.evaluationFunction(currentState)
                move = a
            else:
                if bestScore > alpha:
                    score,move = self.minABPlay(currentState,depth,1,bestScore,beta)
                else:
                    score,move = self.minABPlay(currentState,depth,1,alpha,beta)
            if a == 'Stop':
                score = score - 500
            if score > beta:
                return score,a
            if score > bestScore:
                bestScore = score
                bestMove = a
        
        return bestScore,bestMove

    def minScore(self,gameState,agent):
        worstScore = 99999
        worstMove = ""
        actions = gameState.getLegalActions(agent)
        for a in actions:
            currentState = gameState.generateSuccessor(agent, a)
            score = self.evaluationFunction(currentState)
            if score < worstScore:
                worstScore = score
                worstMove = a


        return worstScore,worstMove



class ExpectimaxAgent(MultiAgentSearchAgent):
    """
      Your expectimax agent (question 4)
    """

    def getAction(self, gameState):
        """
          Returns the expectimax action using self.depth and self.evaluationFunction

          All ghosts should be modeled as choosing uniformly at random from their
          legal moves.
        """
        score,move = self.maxExpPlay(gameState,self.depth)
        return move



    def avgExpPlay(self,gameState,depth,agent):
        """
            Recursively find minimum value for the ghosts
        """
        total = 0
        avgScore = 0
        bestMove = ""
        numAgents = gameState.getNumAgents()
        actions = gameState.getLegalActions(agent)
        for a in actions:
            currentState = gameState.generateSuccessor(agent, a)
            if currentState.isLose() or currentState.isWin():
                score = self.evaluationFunction(currentState)
                move = a
            elif (depth == 1 and agent + 1 == numAgents):
                score = self.evaluationFunction(currentState)
            else:
                if agent + 1 == numAgents:
                    score,move = self.maxExpPlay(currentState,depth - 1)
                else:
                    score = self.avgExpPlay(currentState,depth,agent + 1)
            total = total + score
            total = float(total)
            length = float(len(actions))
            avgScore = total/length
                
        return avgScore


    def maxExpPlay(self,gameState,depth):
        bestScore = -99999
        bestMove = ""
        actions = gameState.getLegalActions(0)
        for a in actions:
            currentState = gameState.generateSuccessor(0, a)
            if currentState.isWin() or currentState.isLose():
                score = self.evaluationFunction(currentState)
                move = a
            else:
                score = self.avgExpPlay(currentState,depth,1)
                move = a
            if score > bestScore:
                bestScore = score
                bestMove = a
        
        return bestScore,bestMove




def betterEvaluationFunction(currentGameState):
    """
      Your extreme ghost-hunting, pellet-nabbing, food-gobbling, unstoppable
      evaluation function (question 5).

      DESCRIPTION: <write something here so we know what you did>
    """

    
    
##    distanceToFood = []  
##    distanceToNearestGhost = []
##    distanceToCapsules = []
##    score = 0
##
##    foodList = currentGameState.getFood().asList()
##    ghostStates = currentGameState.getGhostStates()
##    capsuleList = currentGameState.getCapsules()
##    numOfScaredGhosts = 0
##
##    pacmanPos = list(currentGameState.getPacmanPosition())
##
##    for ghostState in ghostStates:
##        if ghostState.scaredTimer is 0:
##            numOfScaredGhosts += 1
##            distanceToNearestGhost.append(0)
##            continue
##
##        gCoord = ghostState.getPosition()
##        x = abs(gCoord[0] - pacmanPos[0])
##        y = abs(gCoord[1] - pacmanPos[1])
##        if (x+y) == 0:
##            distanceToNearestGhost.append(0)
##        else:
##            distanceToNearestGhost.append(-1.0/(x+y))
##
##    for food in foodList:
##        x = abs(food[0] - pacmanPos[0])
##        y = abs(food[1] - pacmanPos[1])
##        distanceToFood.append(-1*(x+y))  
##
##    if not distanceToFood:
##        distanceToFood.append(0)
##    
##        
##    return max(distanceToFood) + min(distanceToNearestGhost) + scoreEvaluationFunction(currentGameState) - 100*len(capsuleList) - 20*(len(ghostStates) - numOfScaredGhosts)
##



# 5/6
    
    new_pos = currentGameState.getPacmanPosition()
    new_food = currentGameState.getFood()
    new_ghost_states = currentGameState.getGhostStates()
    new_scared_times = [ghostState.scaredTimer for ghostState in new_ghost_states]

    metric = util.manhattanDistance

    ghost_distances = [metric(new_pos, gh.getPosition()) for gh in new_ghost_states]
    if any([dist == 0 for dist in ghost_distances]):
        return -999

    score = scoreEvaluationFunction(currentGameState)
    food_count = currentGameState.getNumFood()
    if food_count == 0:
        return 9999

    near_food_dist = 100
    for i, item in enumerate(new_food):
        for j, foodItem in enumerate(item):
            near_food_dist = min(near_food_dist, metric(new_pos, (i, j)) if foodItem else 100)
    ghost_fun = lambda d: -20 + d**4 if d < 3 else -1.0/d
    ghost_k = sum([ghost_fun(ghost_distances[i]) if new_scared_times[i] < 1 else 0 for i in range(len(ghost_distances))])
    near_food_bonus = 1.0 / near_food_dist
    food_rem_punish = -1.5
    pelete_re_punish = -8 if all((t == 0 for t in new_scared_times)) else 0
    if all((t > 0 for t in new_scared_times)):
        ghost_k *= (-1)
    pelets = currentGameState.getCapsules()
    pelets.sort()
    near_pelet_dist = 100
    if len(pelets) > 0:
        near_pelet_dist = min(near_pelet_dist, min([metric(new_pos, pelet) for pelet in pelets]))
    near_pelet_bonus = 1.0/near_pelet_dist
    peletsRemaining = len(pelets)
    score = score + near_food_bonus + 2 * ghost_k + 10 * near_pelet_bonus + food_rem_punish * food_count + peletsRemaining * pelete_re_punish
    return score



    
##    search = AlphaBetaAgent()
##    searchMove = search.getAction(currentGameState)
    
##
##    bestScore = -99999
##    bestMove = ""
##    actions = currentGameState.getLegalActions(0)
##    for action in actions:
##        successorGameState = currentGameState.generatePacmanSuccessor(action)
##        x,y = successorGameState.getPacmanPosition()
##        newFood = successorGameState.getFood()
##        newGhostStates = successorGameState.getGhostStates()
##        newScaredTimes = [ghostState.scaredTimer for ghostState in newGhostStates]
##
##        if action == searchMove:
##            score = score + 250
##        
##        score = successorGameState.getScore()
##        ghostPositions = successorGameState.getGhostPositions()
##        oldFoodDist = []
##        foodDist = []
##        total = 0
##        index = 0
##        for s in ghostPositions:
##            xy1 = x,y
##            xy2 = s
##            scaredTime = newScaredTimes[index]
##            index = index + 1
##            dist = abs(xy1[0] - xy2[0]) + abs(xy1[1] - xy2[1])
##            if dist <= 1 and scaredTime == 0:
##                score = score - 500
##
##        for a, row in enumerate(newFood):
##            for b, cell in enumerate(row):
##                if newFood[a][b]:
##                    xy = a,b
##                    xyold = currentGameState.getPacmanPosition()
##                    xynew = x,y
##                    distold = abs(xy[0] - xyold[0]) + abs(xy[1] - xyold[1])
##                    distnew = abs(xy[0] - xynew[0]) + abs(xy[1] - xynew[1])
##                    oldFoodDist.append(distold)
##                    foodDist.append(distnew)
##        if oldFoodDist != []:
##            oldMin = min(oldFoodDist)
##            newMin = foodDist[oldFoodDist.index(oldMin)]
##            if newMin < oldMin:
##                score = score + 20
##        
##        if score >= bestScore:
##            bestScore = score
##            bestMove = action
##    return 0

def minABPlay(gameState,depth,agent,alpha,beta):
        """
            Recursively find minimum value for the ghosts
        """
        worstScore = 99999
        worstMove = ""
        numAgents = gameState.getNumAgents()
        actions = gameState.getLegalActions(agent)
        for a in actions:
            currentState = gameState.generateSuccessor(agent, a)
            if currentState.isLose() or currentState.isWin():
                score = scoreEvaluationFunction(currentState)
                move = a
            elif (depth == 1 and (agent + 1) == numAgents):
                score,move = minScore(gameState,agent)
            else:
                if worstScore < beta:
                    beta = worstScore;
                if (agent + 1) == numAgents:
                    score,move = maxABPlay(currentState,depth - 1,alpha,beta)   
                else:
                    score,move = minABPlay(currentState,depth,agent + 1,alpha,beta)
            if score < alpha:
                return score,a
            if score < worstScore :
                worstScore = score
                worstMove = a
                
        return worstScore,worstMove

def maxABPlay(gameState,depth,alpha,beta):
    bestScore = -99999
    bestMove = ""
    actions = gameState.getLegalActions(0)
    for a in actions:
        currentState = gameState.generateSuccessor(0, a)
        
        if currentState.isWin() or currentState.isLose():
            score = scoreEvaluationFunction(currentState)
            move = a
        else:
            if bestScore > alpha:
                score,move = minABPlay(currentState,depth,1,bestScore,beta)
            else:
                score,move = minABPlay(currentState,depth,1,alpha,beta)
        if a == "Stop":
            score = score - 500
        if score > beta:
            return score,a
        if score > bestScore:
            bestScore = score
            bestMove = a
    
    return bestScore,bestMove

def minScore(gameState,agent):
    worstScore = 99999
    worstMove = ""
    actions = gameState.getLegalActions(agent)
    for a in actions:
        currentState = gameState.generateSuccessor(agent, a)
        score = scoreEvaluationFunction(currentState)
        if score < worstScore:
            worstScore = score
            worstMove = a


    return worstScore,worstMove
    
def distance(xy1,xy2):
    return abs(xy1[0] - xy2[0]) + abs(xy1[1] - xy2[1])
                             




# Abbreviation
better = betterEvaluationFunction

