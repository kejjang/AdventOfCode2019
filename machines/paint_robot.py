from typing import List
from collections import defaultdict
from machines.intcode import Intcode


class PaintRobot:
    __computer: Intcode = None
    __direction: int = None  # 0: up, 1: right, 2: down, 3: left
    __coords: List[int] = None
    __panels: defaultdict = None

    def __init__(self, intcode: str = None):
        if intcode is not None:
            self.init_computer(intcode)

        self.__direction = 0
        self.__coords = [0, 0]
        self.__panels = defaultdict(dict)

    def init_computer(self, intcode=None):
        if intcode is not None:
            self.__computer = Intcode(intcode)
        return True

    def set_inputs(self, inputs):
        return self.__computer.set_inputs(inputs)

    def run(self):
        op_type = -1

        while self.__computer.get_status() != Intcode.STATUS_HALT:
            op, status = self.__computer.calc()
            if op == "04":
                op_type = (op_type + 1) % 2
                output = self.__computer.get_signal()

                if op_type == 0:
                    self.__mark(output)
                elif op_type == 1:
                    self.__move(output)
                    self.__computer.set_inputs(self.__read_panel_color())
        return True

    def __mark(self, input):
        color = "." if input == "0" else "#"
        self.__panels[self.__coords[0]][self.__coords[1]] = color
        return True

    def __move(self, input):
        next = 0
        if input == "0":
            next = 3
        elif input == "1":
            next = 1
        self.__direction = (self.__direction + next) % 4
        self.__get_next_step()
        return True

    def __get_next_step(self):
        x_diff = 0
        y_diff = 0

        if self.__direction == 0:
            y_diff -= 1
        elif self.__direction == 1:
            x_diff += 1
        elif self.__direction == 2:
            y_diff += 1
        elif self.__direction == 3:
            x_diff -= 1

        self.__coords = [sum(i) for i in zip(self.__coords, [x_diff, y_diff])]

        return True

    def __read_panel_color(self):
        if self.__coords[0] in self.__panels and self.__coords[1] in self.__panels[self.__coords[0]]:
            color = 1 if self.__panels[self.__coords[0]][self.__coords[1]] == "#" else 0
        else:
            color = 0

        return color

    def get_marked_count(self):
        count = 0
        for x in self.__panels.items():
            count += len(x[1])
        return count

    def paint(self, black=False):
        if black is False:
            black = "."
        if len(black) > 1:
            black = black[:1]

        x = range(min(list(self.__panels.keys())), max(list(self.__panels.keys())) + 1)
        ys = []
        for cols in list(self.__panels.items()):
            ys += [min(list(cols[1].keys()))]
            ys += [max(list(cols[1].keys()))]
        y = range(min(ys), max(ys) + 1)

        for i in y:
            for j in x:
                if j in self.__panels and i in self.__panels[j]:
                    print(self.__panels[j][i].replace(".", black), end="")
                else:
                    print(black, end="")
            print()

        return True
