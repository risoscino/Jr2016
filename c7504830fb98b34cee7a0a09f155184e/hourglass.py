val=input("Input: ")
ind=val.rfind(" ")
h = val[:ind]
p = val[ind:].strip("%")

h = int(h)
p = (int(p))/100

if h >1:
    print("Output:",h)
else:
    print("ERROR: Height must be greater than 1")
    
if p>0:
    if p<1.01:
        print("Output:",p)
    else:
        print("ERROR: Percent must be between 0 and 100")
else:
    print("ERROR: Percent must be between 0 and 100")
