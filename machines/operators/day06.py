from utilities.operator import Base
from machines.orbit_map import OrbitMap


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, map_data):
        orbit_map = OrbitMap(map_data)

        return orbit_map.get_transfer_num()

    def __part2(self, map_data):
        orbit_map = OrbitMap(map_data)
        return orbit_map.get_minimal_transfer_between("YOU", "SAN")
