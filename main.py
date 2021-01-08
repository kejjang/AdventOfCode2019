import sys

from os import path

if len(sys.argv) >= 2 and sys.argv[1].isnumeric():
    n1 = int(sys.argv[1])
    if len(sys.argv) >= 3 and sys.argv[2].isnumeric():
        n2 = int(sys.argv[2]) + 1
    else:
        n2 = n1 + 1
    day_range = range(n1, n2)
else:
    day_range = range(1, 26)


for day_num in day_range:
    filename = f"day{day_num:02d}.py"
    if path.exists(filename):
        print(f"running {filename}\n")
        exec(open(filename).read())
        print("\n================================\n")
