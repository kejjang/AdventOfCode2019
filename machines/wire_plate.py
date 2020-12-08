from typing import Dict, List


class WirePlate:
    __ignore_step: bool = True
    __debug: bool = False
    __wire_num: int = -1
    __loc: Dict[int, Dict[int, Dict[int, int]]] = {}
    __cross: List = []

    def __init__(self, ignore_step: bool = True, debug: bool = False):
        self.set_ignore_step(ignore_step)
        self.set_debug(debug)
        self.__wire_num = -1
        self.__loc = {}
        self.__cross = []

    def set_ignore_step(self, ignore_step: bool = True):
        self.__ignore_step = ignore_step
        return True

    def set_debug(self, debug: bool = False):
        self.__debug = debug
        return True

    def mark_wires(self, wires):
        if type(wires) is list:
            for wire in wires:
                self.mark_wire(wire)
        else:
            self.mark_wire(wires)
        return True

    def mark_wire(self, wire):
        self.__wire_num += 1
        parts = wire.split(",")
        x, y, steps = 0, 0, 0

        if self.__debug:
            print(f"wire {self.__wire_num}\n({x},{y})")

        for part in parts:
            direction = part[0:1]
            distance = part[1:]
            for i in range(int(distance)):
                if direction == "U":
                    y += 1
                elif direction == "D":
                    y -= 1
                elif direction == "L":
                    x -= 1
                elif direction == "R":
                    x += 1
                steps += 1
                self.__set_loc(x, y, self.__wire_num, steps)
            if self.__debug:
                print(f"({x},{y})")
        if self.__debug:
            print()

    def __set_loc(self, x: int, y: int, wire_num: int, steps: int):
        if x not in self.__loc:
            self.__loc[x] = {}
        if y not in self.__loc[x]:
            self.__loc[x][y] = {}
        self.__loc[x][y][wire_num] = 1 if self.__ignore_step else steps
        return True

    def calc_cross(self):
        self.__cross = []
        for x in self.__loc:
            spot = self.__loc[x]
            for y in spot:
                value = spot[y]
                if len(value) > 1:
                    self.__cross += [[x, y, abs(x) + abs(y), value[0] + value[1]]]
        return True

    def get_closet_cross_info(self):
        self.calc_cross()
        compare_index = 2 if self.__ignore_step else 3

        self.__cross.sort(key=lambda item: item[compare_index])
        return self.__cross[0]
