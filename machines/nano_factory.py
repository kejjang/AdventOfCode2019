import math
from typing import List


class NanoFactory:
    __reactions = {}
    __basic = {}
    __wasted = {}
    debug = False

    def __init__(self, reactions: List[str] = None, debug: bool = False):
        self.debug = debug
        self.__reactions = {}
        self.__basic = {}
        self.__wasted = {}
        if reactions is not None:
            self.__reaction_data_parser(reactions)
        self.__find_basic()

    def __reaction_data_parser(self, reactions: List[str]):
        for reaction in reactions:
            input, output = reaction.split(" => ")
            t_quantity, t_chemical = output.split(" ")
            input = input.split(", ")
            materials = {}
            for item in input:
                quantity, chemical = item.split(" ")
                materials[chemical] = int(quantity)
            self.__reactions[t_chemical] = {"quantity": int(t_quantity), "materials": materials}
        return True

    def __find_basic(self):
        for item in self.__reactions:
            if "ORE" in self.__reactions[item]["materials"]:
                self.__basic[item] = 0
        if self.debug:
            print(f"basic: {self.__basic}\n")

    def reset(self):
        self.__basic = {}
        self.__wasted = {}
        self.__find_basic()
        return True

    def calc_ore_requires(self, output="FUEL", require=1):
        self.calc_chemical_requires(output, require)

        if self.debug:
            print(self.__basic)

        ore = 0
        for item in self.__basic:
            m = math.ceil(self.__basic[item] / self.__reactions[item]["quantity"])
            if self.debug:
                print(f"{m} times {self.__reactions[item]['quantity']} {item}:{self.__reactions[item]['materials']['ORE']} ORE")
            ore += self.__reactions[item]["materials"]["ORE"] * m
        if self.debug:
            print(self.__wasted)
        return ore

    def calc_chemical_requires(self, output="FUEL", require=1):
        materials = self.__reactions[output]["materials"]
        quantity = self.__reactions[output]["quantity"]

        m = math.ceil(require / quantity)
        self.__wasted[output] = self.__wasted.get(output, 0) + (m * quantity - require)
        if (m * quantity - require) > 0:
            if self.debug:
                print(f"will wasted {(m * quantity - require)} {output}")

        for item in materials:
            if item in self.__basic:
                if self.debug:
                    print(f"{materials[item]} {item} for {quantity} {output}")
                    print(f"{item} * {materials[item] * m}\n")
                self.__basic[item] += materials[item] * m
            else:
                if self.debug:
                    print(f"{materials[item]} {item} for {quantity} {output}")
                if item in self.__wasted and self.__wasted[item] > 0:
                    if self.debug:
                        print(f"previous wasted {item}: {self.__wasted[item]}")
                    if materials[item] * m <= self.__wasted[item]:
                        if self.debug:
                            print(f"use {materials[item] * m} previous wasted {item}, {self.__wasted[item] - materials[item] * m} left")
                        self.__wasted[item] -= materials[item] * m
                        continue
                    else:
                        a, self.__wasted[item] = self.__wasted[item], 0
                        if self.debug:
                            print(f"use {a} previous wasted {item}, 0 left")
                        self.calc_chemical_requires(item, materials[item] * m - a)
                else:
                    self.calc_chemical_requires(item, materials[item] * m)
        return True

    def max_produce(self, ore=1000000000000):
        req_basic = self.calc_ore_requires(require=1)
        max_p = math.ceil(ore / req_basic)
        self.reset()

        while 1:
            ore_needs = self.calc_ore_requires(require=max_p)
            if self.debug:
                print(max_p, ore_needs, ore)
            if ore_needs > ore:
                break

            guess_ore_plus = (ore - ore_needs) // req_basic
            if guess_ore_plus == 0:
                max_p += 1
            else:
                max_p += guess_ore_plus
            self.reset()

        return max_p - 1
