
class Board 
	
	attr_accessor :position, :board, :current_player
	
    def initialize
    	arr = []
    	8.times { arr << ([nil] * 8)}
    	@board = arr 
    end
      
    def board 
    	@board
    end 
    
    def empty?(square)
    	if board[square[0]][square[1]] == nil 
    		return true 
    	else 
    		return false
    	end 
    end 

    
    
    def in_check?(current_player_color)
    	attacked_squares = []
    	current_player_king_location = []
    	self.board.each_with_index do |row, r_index|
    		row.each_with_index do |element, e_index|
    			if element != nil && element.color != current_player_color
    				element.attacking?(self, [0,0]).each do |square|
    					attacked_squares << square 
    				end 
    			end 
    			if element.is_a?(King) && element.color == current_player_color
    				current_player_king_location.push(r_index, e_index)
    			end 
    		end 
    	end 
    	if attacked_squares.include?(current_player_king_location)
    		return true 
    	else 
    		return false 
    	end 
    end 
    
    def checkmate(current_player_color)
    	current_player_king_location = []
    	attacking_piece_locations = []
    	all_attacked_squares = []
    	all_friendly_squares = []
    	self.board.each_with_index do |row, r_index|
    		row.each_with_index do |element, e_index|
    			if element.is_a?(King) && element.color == current_player_color 
    				 current_player_king_location.push(r_index, e_index)
    			end 
    		end 
    	end 
    	self.board.each_with_index do |row, r_index|
    		row.each_with_index do |element, e_index|
    			if element != nil && element.color != current_player_color 
    				element.attacking?(self, [0,0]).each { |square| all_attacked_squares << square } 
    			elsif element != nil && element.color == current_player_color && element.is_a?(King) == false 
    				element.attacking?(self, [0,0]).each { |square| all_friendly_squares << square }
    			end 
    			if element != nil && element.attacking?(self, [0,0]).include?(current_player_king_location) && element.color != current_player_color
    				attacking_piece_locations << [r_index, e_index]
    			end 
    		end 
    	end
    	if attacking_piece_locations.length == 2 
    		arr = board[current_player_king_location[0]][current_player_king_location[1]].attacking?(self, [0,1])
    		arr.delete_if { |remaining_square| all_attacked_squares.include?(remaining_square) }
    		arr.delete_if { |remaining_square| self.board[remaining_square[0]][remaining_square[1]] != nil }
    		if arr.length == 0 
    			return true 
    		end 
    	elsif attacking_piece_locations.length == 1 
    
    	
    		## you can take the piece, the capturing piece is not pinned 
    	
    		if all_friendly_squares.include?(attacking_piece_locations.first)
    			return false  
    		
    		# you can block, assuming the blocking piece isn't pinned 
    			
    		elsif self.board[attacking_piece_locations[0][0]][attacking_piece_locations[0][1]].is_a?(Queen)
    			attack_piece = self.board[attacking_piece_locations[0][0]][attacking_piece_locations[0][1]]
    			if attack_piece.attack_down(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_down(self, [0,0])
    			elsif attack_piece.attack_up(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_up(self, [0,0])
    			elsif attack_piece.attack_right(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_right(self, [0,0])
    			elsif attack_piece.attack_left(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_left(self, [0,0]) 
    			elsif attack_piece.attack_down_left(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_down_left(self, [0,0])
    			elsif attack_piece.attack_down_right(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_down_right(self, [0,0]) 
    			elsif attack_piece.attack_up_right(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_up_right(self, [0,0]) 
    			elsif attack_piece.attack_up_left(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_up_left(self, [0,0]) 
    			end 
    		elsif self.board[attacking_piece_locations[0][0]][attacking_piece_locations[0][1]].is_a?(Bishop)
    			attack_piece = self.board[attacking_piece_locations[0][0]][attacking_piece_locations[0][1]]
    		 	if attack_piece.attack_down_left(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_down_left(self, [0,0])
    			elsif attack_piece.attack_down_right(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_down_right(self, [0,0]) 
    			elsif attack_piece.attack_up_right(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_up_right(self, [0,0]) 
    			elsif attack_piece.attack_up_left(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_up_left(self, [0,0]) 
    			end 
    		elsif self.board[attacking_piece_locations[0][0]][attacking_piece_locations[0][1]].is_a?(Rook)
    			attack_piece = self.board[attacking_piece_locations[0][0]][attacking_piece_locations[0][1]]
    			if attack_piece.attack_down(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_down(self, [0,0])
    			elsif attack_piece.attack_up(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_up(self, [0,0])
    			elsif attack_piece.attack_right(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_right(self, [0,0])
    			elsif attack_piece.attack_left(self, [0,0]).include?(current_player_king_location)
    				attack_lane = attack_piece.attack_left(self, [0,0]) 
    			end 
    		end 
    			attack_lane.delete(current_player_king_location)
    			intersection = attack_lane & all_friendly_squares 
    			if intersection.length == 0 
    				return true 
    			end 
    
    		 
    		# you can move your king 
    		
    		arr = board[current_player_king_location[0]][current_player_king_location[1]].attacking?(self, [0,1])
    		arr.delete_if { |remaining_square| all_attacked_squares.include?(remaining_square) }
    		arr.delete_if { |remaining_square| self.board[remaining_square[0]][remaining_square[1]] != nil }
    		if arr.length == 0 
    			return true 
    		end 
    			
    
    		
    	
    	end 
    end 
    
    
    def setup
    	arr = []
    	rook1 = Rook.new([0,0], "white")
    	knight1 = Knight.new([1,0], "white")
    	bishop1 = Bishop.new([2,0], "white")
    	queen = Queen.new([3,0] , "white")
    	king = King.new([4,0], "white")
    	bishop2 = Bishop.new([5,0], "white")
    	knight2 = Knight.new([6,0], "white")
    	rook2 = Rook.new([7,0] , "white")
    	arr.push(rook1,knight1,bishop1,queen,king,bishop2,knight2,rook2)
    	arr.each {|element| self.put_on_board(element)}
    	arr = []
    	b_rook1 = Rook.new([0,7], "black")
    	b_knight1 = Knight.new([1,7], "black")
    	b_bishop1 = Bishop.new([2,7], "black")
    	b_queen = Queen.new([3,7], "black")
    	b_king = King.new([4,7], "black")
    	b_bishop2 = Bishop.new([5,7], "black")
    	b_knight2 = Knight.new([6,7], "black")
    	b_rook2 = Rook.new([7,7], "black")
    	arr.push(b_rook1,b_knight1,b_bishop1,b_queen,b_king,b_bishop2,b_knight2,b_rook2)
    	arr.each {|element| self.put_on_board(element)}
    	self.board.each_with_index do |row,index1|
    		row.each_with_index do |element, index2|
    			if index2 == 1 
    				self.put_on_board(Pawn.new([index1,index2] , "white"))
    			elsif index2 == 6
    				self.put_on_board(Pawn.new([index1,index2] , "black"))
    			end
    		end 
    	end 
    			
    end 
   
    
    def display_board
    	printed_board = []
    	@board.each do |row|
    		row.each do |element|
    			if element.is_a?(Pawn) && element.color == 'white'
    				element = :wP
    			elsif element.is_a?(King) && element.color == 'white'
    				element = :wK 
    			elsif element.is_a?(Queen) && element.color == 'white'
    				element = :wQ 
    			elsif element.is_a?(Knight) && element.color == 'white'
    				element = :wN 
    			elsif element.is_a?(Bishop) && element.color == 'white'
    				element = :wB 
    			elsif element.is_a?(Rook) && element.color == 'white'
    				element = :wR 
    			elsif element.is_a?(Pawn) && element.color == 'black'
    				element = :bP
    			elsif element.is_a?(King) && element.color == 'black'
    				element = :bK 
    			elsif element.is_a?(Queen) && element.color == 'black'
    				element = :bQ 
    			elsif element.is_a?(Knight) && element.color == 'black'
    				element = :bN 
    			elsif element.is_a?(Bishop) && element.color == 'black'
    				element = :bB 
    			elsif element.is_a?(Rook) && element.color == 'black'
    				element = :bR 
    			end 
    			printed_board << element 
    		end 
    	end 
    	printed_board = printed_board.each_slice(8).to_a
    	printed_board.each do |rows|
    		puts rows.to_s 
    	end 
    end 
    
    
    def put_on_board(piece)
    	@board[piece.position[0]][piece.position[1]] = piece 
    end 
   

end 

class Piece 
	
	attr_accessor :board, :current_player, :moved
	
	def initialize(position, color) 
		@position = position
		@color = color 
		@moved = false 
	end 
	
	def color 
		@color
	end 
	
	def was_moved
		@moved = true
	end 
	
	
	def position 
		@position
	end 	
	
	def move(board,space_arr)
		if valid_move?(board, space_arr) == false 
			return false 
		end 
		@position = space_arr
		board.board.each do |row|
			row.collect! do |element|
				element == self ? nil : element 
			end 
		end 
		board.board[@position[0]][@position[1]] = self 
		self.was_moved
	end 
	
	
	def valid_move?(board, square)

		if attacking?(board, square).include?(square) && (board.board[square[0]][square[1]] == nil || board.board[square[0]][square[1]].color != self.color)
			return true 
		else 
			return false 
		end 
	end 
	
	
	def attack_right(board, square)
		squares_attacked = []
		x = 1 
		if self.position[1] == 7 
			return squares_attacked
		end
		while board.board[self.position[0]][self.position[1] + x] == nil 
			break if self.position[1] + x == 7
			squares_attacked << [self.position[0], self.position[1] + x]
			x = x + 1 
		end 
		squares_attacked << [self.position[0], self.position[1] + x ]
	end 

	
	def attack_left(board, square)
		squares_attacked = []
		x = 1 
		if self.position[1] == 0 
			return squares_attacked
		end 
		while board.board[self.position[0]][self.position[1] - x] == nil 
			break if self.position[1] - x == 0
			squares_attacked << [self.position[0], self.position[1] - x]
			x = x + 1 
		end 
		squares_attacked << [self.position[0], self.position[1] - x ]
	end 
	
	def attack_up(board, square)
		squares_attacked = []
		x = 1 
		if self.position[0] == 0 
			return squares_attacked 
		end 
		while board.board[self.position[0] - x ][self.position[1]] == nil
			break if self.position[0] - x == 0
			squares_attacked << [self.position[0] - x , self.position[1]]
			x = x + 1 
		end 
		squares_attacked << [self.position[0] - x , self.position[1]]
	end 
	
	def attack_down(board, square)
		squares_attacked = [] 
		x = 1 
		if self.position[0] == 7 
			return squares_attacked 
		end 
		while board.board[self.position[0] + x ][self.position[1]] == nil
			break if self.position[0] + x == 7
			squares_attacked << [self.position[0] + x , self.position[1]]
			x = x + 1 
		end 
		squares_attacked << [self.position[0] + x , self.position[1]]
	end 
	
	

end 


class Pawn < Piece 
	
	
	def attacking?(board, square)
		squares_attacked = []
		if self.color == "white"
			if self.position[0] != 7 
				squares_attacked << [self.position[0] + 1 , self.position[1] + 1]
			end 
			if self.position[0] != 0
				squares_attacked << [self.position[0] - 1 , self.position[1] + 1]
			end 
		elsif self.color == "black"
			if self.position[0] != 0 
				squares_attacked << [self.position[0] - 1 , self.position[1] - 1]
			end 
			if self.position[0] != 7 
				squares_attacked << [self.position[0] + 1 , self.position[1] - 1]
			end 
		end 
		squares_attacked
	end 
	
	def move_forward(board, square)
		forward_spaces = []
		if self.color == "white" && board.board[self.position[0]][self.position[1] + 1] == nil 
			forward_spaces << [self.position[0] , self.position[1] + 1]
		end 
		if self.color == "black" && board.board[self.position[0]][self.position[1] - 1] == nil 
			forward_spaces << [self.position[0] , self.position[1] - 1]
		end 
		if self.moved == false 
			if self.color == "white" && board.board[self.position[0]][self.position[1] + 2] == nil && board.board[self.position[0]][self.position[1] + 1] == nil
				forward_spaces << [self.position[0] , self.position[1] + 2]
			end 
			if self.color == "black" && board.board[self.position[0]][self.position[1] - 2] == nil && board.board[self.position[0]][self.position[1] - 1] == nil
				forward_spaces << [self.position[0], self.position[1] - 2]
			end 
		end 
 		forward_spaces
	end 
	
	def valid_move?(board, square)
		if attacking?(board, square).include?(square) && (board.board[square[0]][square[1]].color != self.color || board.board[square[0]][square[1]] == nil)
			return true 
		end

		if move_forward(board,square).include?(square)
			return true 
		end 
			
		return false 
	end 
	

end 

class King < Piece 	
	
	
	def move(board,space_arr)
		if valid_move?(board, space_arr) == false 
			return false 
		elsif valid_move?(board, space_arr) == :castle_k_side && self.color == "white"
			board.board[space_arr[0]][space_arr[1]] = self 
			board.board[4][0] = nil 
			board.board[5][0] = board.board[7][0]
			board.board[7][0] = nil 
			return true 
		elsif valid_move?(board, space_arr) == :castle_q_side && self.color == "white"
			board.board[space_arr[0]][space_arr[1]] = self 
			board.board[4][0] = nil 
			board.board[3][0] = board.board[0][0]
			board.board[0][0] = nil 
			return true 
		elsif valid_move?(board, space_arr) == :castle_k_side && self.color == "black"
			board.board[space_arr[0]][space_arr[1]] = self
			board.board[4][7] = nil 
			board.board[5][7] = board.board[7][7]
			board.board[7][7] = nil 
			return true
		elsif valid_move?(board, space_arr) == :castle_q_side && self.color == "black"
			board.board[space_arr[0]][space_arr[1]] = self 
			board.board[4][7] = nil 
			board.board[3][7] = board.board[0][7]
			board.board[0][7] = nil 
			return true
		end 
		@position = space_arr
		board.board.each do |row|
			row.collect! do |element|
				element == self ? nil : element 
			end 
		end 
		board.board[@position[0]][@position[1]] = self 
		self.was_moved
	end 
	
	
	def castle_k_side?(board, square)
		if board.board[4][0] != nil && board.board[4][0].moved == false && board.board[7][0].moved == false && board.board[5][0] == nil && board.board[6][0] == nil 
			return true 
		end 
		if board.board[4][7] != nil && board.board[4][7].moved == false && board.board[7][7].moved == false && board.board[5][7] == nil && board.board[6][7] == nil 
			return true 
		end 
		return false 
	end 
	
	def castle_q_side?(board, square)
		if board.board[4][0] != nil && board.board[4][0].moved == false && board.board[0][0].moved == false && board.board[1][0] == nil && board.board[2][0] == nil && board.board[3][0] == nil 
			return true 
		end 
		if board.board[4][7] != nil && board.board[4][7].moved == false && board.board[7][7].moved == false && board.board[3][7] == nil && board.board[2][7] == nil && board.board[1][7] == nil 
			return true 
		end 
		return false 
	end 
	
	
	def valid_move?(board, square)
		if castle_k_side?(board, square) == true && (square == [6,0] || square == [6,7])
			return :castle_k_side
		end 
		
		if castle_q_side?(board, square) == true && (square == [2,0] || square == [2,7])
			return :castle_q_side
		end 

		if attacking?(board, square).include?(square) && (board.board[square[0]][square[1]] == nil || board.board[square[0]][square[1]].color != self.color)
			return true 
		else 
			return false 
		end 
	end 


	
	def attack_up(board, square)
		if self.position[0] == 0 
			return []
		end 
		[[self.position[0] - 1 , self.position[1]]]
	end 
	
	
	def attack_down(board, square)
		if self.position[0] == 7 
			return []
		end 
		[[self.position[0] + 1 , self.position[1]]]
	end 
	
	def attack_left(board, square)
		if self.position[1] == 0 
			return []
		end 
		[[self.position[0] , self.position[1] - 1 ]]
	end 
	
	def attack_right(board, square)
		if self.position[1] == 7 
			return []
		end 
		[[self.position[0] , self.position[1] + 1 ]]
	end 
	
	def attack_down_right(board, square)
		if self.position[0] == 7 || self.position[1] == 7 
			return [] 
		end 
		[[self.position[0] + 1, self.position[1] + 1 ]]
	end 
	
	def attack_down_left(board, square)
		if self.position[0] == 7 || self.position[1] == 0
			return []
		end 
		[[self.position[0] + 1, self.position[1] - 1 ]]
	end 
	
	def attack_up_left(board, square)
		if self.position[0] == 0 || self.position[1] == 0
			return []
		end 
		[[self.position[0] - 1, self.position[1] - 1 ]]
	end 
	
	def attack_up_right(board, square)
		if self.position[0] == 0 || self.position[1] == 7 
			return []
		end 
		[[self.position[0] - 1, self.position[1] + 1 ]]
	end 
	
	def attacking?(board, square)
		attack_down(board, square) + attack_up(board, square) + attack_left(board, square) + attack_right(board, square) + 
		attack_down_left(board, square) + attack_down_right(board, square) + attack_up_left(board, square) + attack_up_right(board, square)
	end 


end 

class Bishop < Piece 

	
	def attack_up_left(board, square)
		squares_attacked = []
		x = 1 
		if self.position[0] == 0 || self.position[1] == 0
			return squares_attacked
		end 
		while board.board[self.position[0] - x][self.position[1] - x] == nil 
			break if self.position[0] - x == 0 || self.position[1] - x == 0 
			squares_attacked << [self.position[0] - x, self.position[1] - x]
			x = x + 1 
		end 
		squares_attacked << [self.position[0] - x , self.position[1] - x ]
	end 
	
	
	def attack_up_right(board, square)
		squares_attacked = []
		x = 1
		if self.position[0] == 0 || self.position[1] == 7
			return squares_attacked
		end
		while board.board[self.position[0] - x][self.position[1] + x] == nil 
			break if self.position[0] - x == 0 || self.position[1] + x == 7 
			squares_attacked << [self.position[0] - x, self.position[1] + x]
			x = x + 1 
		end 
		squares_attacked << [self.position[0] - x, self.position[1] + x]
	end 
	
	
	def attack_down_left(board, square)
		squares_attacked = []
		x = 1 
		if self.position[0] == 7 || self.position[1] == 0
			return squares_attacked 
		end 
		while board.board[self.position[0] + x][self.position[1] - x] == nil
			break if self.position[0] + x == 7 || self.position[1] - x == 0 
			squares_attacked << [self.position[0] + x, self.position[1] - x]
			x = x + 1 
		end 
		squares_attacked << [self.position[0] + x , self.position[1] - x]
	end 
	
	def attack_down_right(board, square)
		squares_attacked = []
		if self.position[0] == 7 || self.position[1] == 7
			return squares_attacked 
		end 
		x = 1 
		while board.board[self.position[0] + x][self.position[1] + x] == nil
			break if self.position[0] + x == 7 || self.position[1] + x == 7 
			squares_attacked << [self.position[0] + x, self.position[1] + x]
			x = x + 1 
		end 
		squares_attacked << [self.position[0] + x , self.position[1] + x]
	end 
	
	def attacking?(board, square)
		attack_down_left(board, square) +  attack_down_right(board, square) + attack_up_right(board, square) + attack_up_left(board, square)
	end 
	
	

end 
	

class Knight < Piece 
	def attacking?(board, square)
		attacked_squares = []
		if [self.position[0] - 2, self.position[1] - 1] != nil 
			attacked_squares << [self.position[0] - 2, self.position[1] - 1]
		end 
		if [self.position[0] - 2, self.position[1] + 1] != nil 
			attacked_squares << [self.position[0] - 2, self.position[1] + 1]
		end 
		if [self.position[0] - 1, self.position[1] - 2] != nil 
			attacked_squares << [self.position[0] - 1, self.position[1] - 2]
		end 
		if [self.position[0] - 1, self.position[1] + 2] != nil
			attacked_squares << [self.position[0] - 1, self.position[1] + 2]
		end 
		if [self.position[0] + 1, self.position[1] - 2] != nil 
			attacked_squares << [self.position[0] + 1, self.position[1] - 2]
		end 
		if [self.position[0] + 1, self.position[1] + 2] != nil 
			attacked_squares << [self.position[0] + 1, self.position[1] + 2]
		end 
		if attacked_squares << [self.position[0] + 2, self.position[1] - 1] != nil
			attacked_squares << [self.position[0] + 2, self.position[1] - 1]
		end 
		if attacked_squares << [self.position[0] + 2, self.position[1] + 1] !=nil 
			attacked_squares << [self.position[0] + 2, self.position[1] + 1]
		end 
		attacked_squares
	end 
	
	
end 

class Rook < Piece 
	

	def attacking?(board, square)
		attack_down(board, square) + attack_up(board, square) + attack_left(board, square) + attack_right(board, square)
	end 

end 

class Queen < Bishop 
	
	def attacking?(board, square)
		attack_down(board, square) + attack_up(board, square) + attack_left(board, square) + attack_right(board, square) + attack_down_left(board, square) +  attack_down_right(board, square) + attack_up_right(board, square) + attack_up_left(board, square)
	end 

end 

class Player 
	
	def initialize(color)
		@color = color 
	end 
	
	def color 
		@color
	end 

	def which_piece?
		puts "Where is the piece you want to move?"
		piece_position = gets.chomp
		piece_position_arr = piece_position.split(",")
		piece_position_arr.map {|element| element.to_i } 
	end 
	
	def to_where? 
		puts "Where do you want to move it?"
		move_string = gets.chomp 
		move_arr = move_string.split(",")
		move_arr.map { |element| element.to_i }
	end 
	
	def out_of_bounds?(user_input)
		if user_input[0] > 7 || user_input[0] < 0 || user_input[1] > 7 || user_input[1] < 0 
			return true
		else 
			return false
		end 
	end 



end

class Game 
	
	attr_accessor :current_player
	
	def initialize(player1, player2, board)
		@player1 = player1 
		@player2 = player2 
		@board = board 
		@current_player = player1 
	end 
	
	def current_player
		@current_player 
	end 
	
	def setup
		@board.setup
	end 
	
	
	def board 
		@board 
	end 
	
	
	def switch_players!
		if current_player == @player1 
			@current_player = @player2 
		elsif current_player == @player2 
			@current_player = @player1 
		end 
	end 
	
	
	def play 
		board.display_board
		while true 
			if board.in_check?(current_player.color) == true 
				if board.checkmate(current_player.color) != true 
					puts "Check!"
				elsif board.checkmate(current_player.color) == true 
					puts "Checkmate!"
				break
				end 
			end 
			piece_to_move = false
			until piece_to_move == true  
				position_of_piece = current_player.which_piece?
				move_to_square = current_player.to_where?
				while current_player.out_of_bounds?(position_of_piece) || current_player.out_of_bounds?(move_to_square) || 
				self.board.board[position_of_piece[0]][position_of_piece[1]] == nil || current_player.color != self.board.board[position_of_piece[0]][position_of_piece[1]].color || 
				board.in_check?(current_player.color) 
					puts "You can only move your own pieces, that are on the board"
					position_of_piece = current_player.which_piece?
					move_to_square = current_player.to_where?
				end 
				piece_to_move = self.board.board[position_of_piece[0]][position_of_piece[1]]
				piece_to_move = piece_to_move.move(self.board, move_to_square)
				if piece_to_move == false 
					puts "Please enter a valid move!" 
				end 
			end 
			board.display_board 
			switch_players!
		end 
	
	end 
	
	
end 


player1 = Player.new("white")
player2 = Player.new("black")
board = Board.new 
game = Game.new(player1, player2, board)
game.setup
game.play