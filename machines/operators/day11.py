from utilities.operator import Base
from machines.paint_robot import PaintRobot


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, intcode):
        robot = PaintRobot(intcode)
        robot.set_inputs(0)
        robot.run()
        return robot.get_marked_count()

    def __part2(self, intcode):
        robot = PaintRobot(intcode)
        robot.set_inputs(1)
        robot.run()
        robot.paint(" ")
        return ""
