from utilities.operator import Base
from machines.intcode import Intcode


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, intcode):
        computer = Intcode(intcode)
        computer.set_inputs(1)

        while computer.get_status() != Intcode.STATUS_HALT:
            computer.calc()

        return computer.get_signal()

    def __part2(self, intcode):
        computer = Intcode(intcode)
        computer.set_inputs(5)

        while computer.get_status() != Intcode.STATUS_HALT:
            computer.calc()

        return computer.get_signal()
