from utilities.operator import Base
from machines.wire_plate import WirePlate


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, wires):
        plate = WirePlate(True, False)
        plate.mark_wires(wires)

        info = plate.get_closet_cross_info()
        return info[2]

    def __part2(self, wires):
        plate = WirePlate(False, False)
        plate.mark_wires(wires)

        info = plate.get_closet_cross_info()
        return info[3]
