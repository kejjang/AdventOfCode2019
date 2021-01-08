from utilities.operator import OperatorBase
from machines.arcade_cabinet import ArcadeCabinet


class Operator(OperatorBase):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, intcode):
        game = ArcadeCabinet(intcode)
        game.run_intcode()
        return game.get_tile_count(2)

    def __part2(self, intcode):
        intcode = "2" + intcode[1:]
        game = ArcadeCabinet(intcode)
        score = game.run_intcode(play=True)
        # print(game.get_draw_data())
        return score
