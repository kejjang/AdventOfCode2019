from typing import List


class Intcode:
    STATUS_STOP = 0
    STATUS_RUNNING = 1
    STATUS_WAIT = 2
    STATUS_HALT = 3

    __code: List[str]
    __status: int
    __signal: str
    __pos: int
    __inputs: List[str]
    __relative_base: int

    def __init__(self, intcode):
        self.__code = intcode.split(",")
        self.__status = self.STATUS_RUNNING
        self.__signal = None
        self.__pos = 0
        self.__inputs = []
        self.__relative_base = 0

    def set_code(self, pos: int, code: str):
        self.__code[pos] = code
        return True

    def set_inputs(self, inputs):
        if type(inputs) is list:
            self.__inputs += inputs
        else:
            self.__inputs += [inputs]
        self.__status = self.STATUS_RUNNING
        return True

    def __get_code_value(self, mode, pos_index):
        pos_value = self.__code[self.__pos + pos_index]
        return_value = False
        if int(mode) == 1:
            return_value = pos_value
        elif int(mode) == 2:
            idx = self.__relative_base + int(pos_value)
            return_value = self.__code[idx] if idx < len(self.__code) else "0"
        else:
            idx = int(pos_value)
            return_value = self.__code[idx] if idx < len(self.__code) else "0"
        return return_value

    def __set_code_value(self, mode, pos_index, value):
        value = str(value)
        pos_value = self.__code[self.__pos + pos_index]
        final_index = None
        if int(mode) == 2:
            final_index = self.__relative_base + int(pos_value)
        else:
            final_index = int(pos_value)

        if final_index >= len(self.__code):
            self.__code += [None] * (final_index - len(self.__code) + 1)

        self.__code[final_index] = value
        return True

    def calc(self):
        inst = f"{int(self.__code[self.__pos]):05d}"

        op = inst[-2:]
        mode1 = inst[2:3]
        mode2 = inst[1:2]
        mode3 = inst[0:1]

        jump = False

        if op == "99":
            self.__status = self.STATUS_HALT
        elif op == "01":
            n1 = self.__get_code_value(mode1, 1)
            n2 = self.__get_code_value(mode2, 2)

            n3 = int(n1) + int(n2)
            self.__set_code_value(mode3, 3, n3)

            self.__pos += 4
        elif op == "02":
            n1 = self.__get_code_value(mode1, 1)
            n2 = self.__get_code_value(mode2, 2)

            n3 = int(n1) * int(n2)
            self.__set_code_value(mode3, 3, n3)

            self.__pos += 4
        elif op == "03":
            if len(self.__inputs) > 0:
                input = self.__inputs.pop(0)
            else:
                input = False

            if input is False:
                self.__status = self.STATUS_WAIT
            else:
                self.__set_code_value(mode1, 1, input)
                self.__pos += 2
        elif op == "04":
            self.__signal = self.__get_code_value(mode1, 1)

            self.__pos += 2
            self.__status = self.STATUS_WAIT
        elif op == "05":
            n1 = self.__get_code_value(mode1, 1)
            n2 = self.__get_code_value(mode2, 2)

            if n1 != "0":
                jump = int(n2) - self.__pos
            else:
                jump = 3

            self.__pos += jump
        elif op == "06":
            n1 = self.__get_code_value(mode1, 1)
            n2 = self.__get_code_value(mode2, 2)

            if n1 == "0":
                jump = int(n2) - self.__pos
            else:
                jump = 3

            self.__pos += jump
        elif op == "07":
            n1 = self.__get_code_value(mode1, 1)
            n2 = self.__get_code_value(mode2, 2)

            if int(n1) < int(n2):
                self.__set_code_value(mode3, 3, 1)
            else:
                self.__set_code_value(mode3, 3, 0)
            self.__pos += 4
        elif op == "08":
            n1 = self.__get_code_value(mode1, 1)
            n2 = self.__get_code_value(mode2, 2)

            if int(n1) == int(n2):
                self.__set_code_value(mode3, 3, 1)
            else:
                self.__set_code_value(mode3, 3, 0)
            self.__pos += 4
        elif op == "09":
            n1 = self.__get_code_value(mode1, 1)
            self.__relative_base += int(n1)
            self.__pos += 2

        return [op, self.__status]

    def get_signal(self):
        return self.__signal

    def get_status(self):
        return self.__status

    def get_code(self):
        return ",".join(self.__code)
