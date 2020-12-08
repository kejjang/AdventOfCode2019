from typing import List
from machines.intcode import Intcode


class ArcadeCabinet:
    __computer: Intcode = None
    __draw_data: List[int] = None

    def __init__(self, intcode: str = None):
        self.__draw_data = []
        if intcode is not None:
            self.init_computer(intcode)

    def init_computer(self, intcode=None):
        if intcode is not None:
            self.__computer = Intcode(intcode)

    def set_inputs(self, inputs):
        return self.__computer.set_inputs(inputs)

    def run_intcode(self):
        draw_data = []
        while self.__computer.get_status() != Intcode.STATUS_HALT:
            op, status = self.__computer.calc()
            if op == "04":
                output = self.__computer.get_signal()
                draw_data += [output]
                if int(output) in [0, -1, 1]:
                    self.set_inputs(output)
        n = 3
        self.__draw_data = [draw_data[i : i + n] for i in range(0, len(draw_data), n)]
        return True

    def get_draw_data(self):
        return self.__draw_data

    def get_tile_count(self, tile_type):
        count = 0
        for part in self.__draw_data:
            if part[2] == str(tile_type):
                count += 1
        return count
