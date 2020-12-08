from utilities.operator import Base
from machines.passwd_utility import PasswdUtility


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, ranges):
        return PasswdUtility.guessCount(ranges)

    def __part2(self, ranges):
        return PasswdUtility.guessCount(ranges, True)
