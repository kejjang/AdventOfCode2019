from utilities.operator import Base
from machines.arcade_cabinet import ArcadeCabinet


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, intcode):
        game = ArcadeCabinet(intcode)
        game.run_intcode()
        return game.get_tile_count(2)

    def __part2(self, intcode):
        intcode = "2" + intcode[1:]
        game = ArcadeCabinet(intcode)
        game.run_intcode()
        print(game.get_draw_data())
