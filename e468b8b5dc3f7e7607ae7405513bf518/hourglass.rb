def setup(rows, percent)
	array = []
	i = rows * 2 - 1
	places = 0 
	while i > 0 
		array.push([nil] * i)
				places = places + i
		i = i - 2
	end 
	i = 1
	while i <= rows * 2 - 1  
		array.push([nil] * i)
		i = i + 2
	end 
	middle = 0 
	array.each_with_index do |little, index|
		if little.length == 1 
			middle = index 
		end 
	end 
	bottom = []
	for x in 0...array.length
		bottom << array[x]
		break if array[x].length == 1 
	end 
	top = bottom.reverse 
	if (places * (percent / 100.0)).to_i != (places * (percent / 100.0))
		top_exes = (places * (percent / 100.0)).to_i + 1 
	else 
		top_exes = (places * (percent / 100.0)).to_i
	end 
	bottom_exes = places - top_exes
	return [top, top_exes, bottom, bottom_exes]
end


def populate_top(top_arr, top_exes)
	top_arr.map do |a|
		left = 0 
		right = a.length - 1 
		while a.include?(nil) && top_exes > 0 
			a[right] = "x"
			top_exes = top_exes - 1 
			right = right - 1 
			break if top_exes == 0 || a.include?(nil) == false 
			a[left] = "x"
			left = left + 1 
			top_exes = top_exes - 1 
			break if top_exes == 0 
		end 
	end 
	top_arr
end 


def populate_bottom(bottom_arr, bottom_exes)
	bottom_arr.map do |arr|
		middle = arr.length / 2 
		left = middle - 1 
		right = middle + 1 
		while arr.include?(nil) && bottom_exes > 0 
			if arr[middle] == nil 
				arr[middle] = "x"
				bottom_exes = bottom_exes - 1  
			end 
			break if bottom_exes == 0 || arr.include?(nil) == false 
			arr[right] = "x"
			right = right + 1 
			bottom_exes = bottom_exes - 1  
			break if bottom_exes == 0 || arr.include?(nil) == false 
			arr[left] = "x"
			bottom_exes = bottom_exes - 1  
			left = left - 1 
		end 
	end 
	bottom_arr
end


def print_hourglass
	puts "How many rows?"
	rows = gets.chomp.to_i
	puts "What percent?"
	percent = gets.chomp.to_i
	bottom_arr = setup(rows,percent)[2]
	bottom_exes = setup(rows,percent)[3]
	top_arr = setup(rows,percent)[0]
	top_exes = setup(rows,percent)[1]


top_arr = populate_top(top_arr,top_exes).reverse
bot_arr = populate_bottom(bottom_arr ,bottom_exes ).reverse 

y = top_arr.map do |sub|
	sub.map do |el|
		el == nil ? el = " " : el 
	end 
end 
top_iterations = 0 
puts "_" * (y[0].length + 1) + "_"
y.each do |sub|
	puts " " * top_iterations + "\\" + sub.join("").to_s + "/"
	top_iterations = top_iterations + 1 
end 



bot = bot_arr.each_with_index.map do |sub, index|
	sub.map do |el|
		if index == bot_arr.length - 1 && el == nil 
			el = "_"
		end 
		el == nil ? el = " " : el 
	end 
end 
bot_iterations = top_iterations - 1
bot.each do |sub|
	puts " " * bot_iterations + "/" + sub.join("").to_s + "\\"
	bot_iterations = bot_iterations - 1 
end 

end 

print_hourglass





