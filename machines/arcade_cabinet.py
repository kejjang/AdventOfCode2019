from typing import List
from machines.intcode import Intcode


class ArcadeCabinet:
    __computer: Intcode = None
    __draw_data: List[int] = None
    __ball_pos: int = None
    __paddle_pos: int = None
    __score: int = None

    def __init__(self, intcode: str = None):
        self.__draw_data = []
        if intcode is not None:
            self.init_computer(intcode)

    def init_computer(self, intcode=None):
        if intcode is not None:
            self.__computer = Intcode(intcode)

    def set_inputs(self, inputs):
        return self.__computer.set_inputs(inputs)

    def run_intcode(self, play=False):
        draw_data = []
        while True:
            if self.__computer.get_status() == Intcode.STATUS_HALT:
                break

            if play:
                if None not in [self.__ball_pos, self.__paddle_pos]:
                    if self.__ball_pos > self.__paddle_pos:
                        self.set_inputs(1)
                    elif self.__ball_pos < self.__paddle_pos:
                        self.set_inputs(-1)
                    else:
                        self.set_inputs(0)

            x = self.__run_computer(play)
            y = self.__run_computer(play)

            if x == "-1" and y == "0":
                z = self.__score = self.__run_computer(play)
                # print("score", self.__score)
                # print("ball x", self.__ball_pos)
                # print("paddle x", self.__paddle_pos)
                # print()
            else:
                z = tile = self.__run_computer(play)
                if tile == "3":
                    self.__paddle_pos = int(x)
                elif tile == "4":
                    self.__ball_pos = int(x)

            draw_data += [x, y, z]

        n = 3
        self.__draw_data = [draw_data[i : i + n] for i in range(0, len(draw_data), n)]

        return self.__score

    def __run_computer(self, play=False):
        while self.__computer.get_status() != Intcode.STATUS_HALT:
            op, status = self.__computer.calc()
            if op == "04":
                return self.__computer.get_signal()

    def get_draw_data(self):
        return self.__draw_data

    def get_tile_count(self, tile_type):
        count = 0
        for part in self.__draw_data:
            if part[2] == str(tile_type):
                count += 1
        return count
