from utilities.data import PuzzleInput


class Base:
    def __init__(self, day_num: int = -1, to_int=False):
        if day_num in range(1, 26):
            self.load_data(day_num, to_int)

    def load_data(self, day_num: int = -1, to_int=False):
        if day_num in range(1, 26):
            self.data = PuzzleInput.get(day_num)
            if to_int:
                self.data = [int(item) for item in self.data]
