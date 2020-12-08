from typing import List


class PhaseUtility:
    MODE_GENERAL = 0
    MODE_FEEDBACK = 1

    @staticmethod
    def swap(ary, idx1, idx2):
        [ary[idx1], ary[idx2]] = [ary[idx2], ary[idx1]]
        return ary

    @classmethod
    def factorial(cls, n):
        return 1 if n <= 1 else n * cls.factorial(n - 1)

    @classmethod
    def gen(cls, mode: int):
        if mode == cls.MODE_GENERAL:
            seeds = range(0, 5)
        elif mode == cls.MODE_FEEDBACK:
            seeds = range(5, 10)
        else:
            return False

        phases: List[str] = []
        phases += [",".join([str(i) for i in seeds])]
        pos = len(seeds)

        for seed in seeds:
            for phase in phases:
                last = phase.split(",")
                loc = last.index(str(seed))
                while loc < pos - 1:
                    phases += [",".join([str(i) for i in cls.swap(last, loc, loc + 1)])]
                    count = len(phases)
                    last = phases[count - 1].split(",")
                    loc = last.index(str(seed))
            phases = list(set(phases))

        phases = sorted(list(set(phases)))

        for idx, phase in enumerate(phases):
            phases[idx] = phase.split(",")

        return phases
