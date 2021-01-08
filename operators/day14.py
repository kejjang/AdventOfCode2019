from utilities.operator import OperatorBase
from machines.nano_factory import NanoFactory


class Operator(OperatorBase):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, reactions):
        factory = NanoFactory(reactions)
        return factory.calc_ore_requires()

    def __part2(self, reactions):
        factory = NanoFactory(reactions)
        return factory.max_produce(1000000000000)
