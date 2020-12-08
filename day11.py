from machines.operators.day11 import Operator

op = Operator(day_num=11)

print("# Part One")
print(op.exec(part=1))

print("")

print("# Part Two")
print((r := op.exec(part=2)), end=r)
