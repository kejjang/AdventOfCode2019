from functools import reduce
from utilities.operator import Base


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, masses):
        fuels = reduce(lambda carry, mass: carry + mass // 3 - 2, masses, 0)
        return fuels

    def __part2(self, masses):
        fuels = reduce(lambda carry, mass: carry + self.__calc_fuels(mass), masses, 0)
        return fuels

    def __calc_fuels(self, mass):
        fuels = mass // 3 - 2
        return 0 if fuels <= 0 else fuels + self.__calc_fuels(fuels)
