from utilities.operator import Base
from machines.intcode import Intcode


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, intcode):
        computer = Intcode(intcode)

        computer.set_code(1, "12")
        computer.set_code(2, "2")

        while computer.get_status() != Intcode.STATUS_HALT:
            computer.calc()

        return computer.get_code().split(",")[0]

    def __part2(self, intcode):
        for noun in range(100):
            for verb in range(100):
                computer = Intcode(intcode)
                computer.set_code(1, str(noun))
                computer.set_code(2, str(verb))

                while computer.get_status() != Intcode.STATUS_HALT:
                    computer.calc()
                intcode_calc = computer.get_code().split(",")

                if intcode_calc[0] == "19690720":
                    return 100 * noun + verb
