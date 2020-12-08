from utilities.operator import Base
from machines.moon_detector import MoonDetector


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, data):
        detector = MoonDetector(data)
        detector.motion_simulator(1000)
        return detector.get_energy()

    def __part2(self, data):
        detector = MoonDetector(data)
        return detector.calc_back_init_pos_steps()
