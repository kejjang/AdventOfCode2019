class PasswdUtility:
    def __is_double(passwd):
        digits = set(passwd.replace(" ", ""))
        return False if len(digits) == 6 else True

    def __is_not_decrease(passwd):
        digits = "".join(sorted(list(passwd.replace(" ", ""))))
        return True if passwd == digits else False

    def __is_not_part_of_larger_group(passwd):
        digits = list(passwd.replace(" ", ""))
        counts = {}
        for d in digits:
            if d in counts:
                counts[d] += 1
            else:
                counts[d] = 1
        is_valid = False
        for c in counts:
            if counts[c] == 2:
                is_valid = True
        return is_valid

    @classmethod
    def guess(cls, ranges, not_part_of_larger_group=False):
        possible = []
        for i in range(int(ranges[0]), int(ranges[1]) + 1):
            i = str(i)
            if cls.__is_double(i) and cls.__is_not_decrease(i):
                if not_part_of_larger_group and not cls.__is_not_part_of_larger_group(i):
                    continue
                possible += [i]
        return possible

    @classmethod
    def guessCount(cls, ranges, not_part_of_larger_group=False):
        return len(cls.guess(ranges, not_part_of_larger_group))
