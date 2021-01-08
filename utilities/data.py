import os
import sys


class PuzzleInput:
    @staticmethod
    def get(day_num):
        return [line.strip() for line in open(f"{os.path.dirname(os.path.realpath(sys.argv[0]))}/data/day{day_num:02d}.txt", "r").readlines()]
