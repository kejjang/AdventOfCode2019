import json

from typing import List

# from copy import deepcopy


class MoonDetector:
    moons = None

    def __init__(self, data=None):
        self.moons = []
        if data is not None:
            self.set_data(data)

    def set_data(self, data=None):
        return self.__parse_data(data) if data is not None else False

    def __parse_data(self, data: List[str]):
        for line in data:
            moon = {
                "pos_init": [0, 0, 0],
                "pos": [0, 0, 0],
                "vel": [0, 0, 0],
            }
            index = {"x": 0, "y": 1, "z": 2}

            positions = line.replace("<", "").replace(">", "").split(",")
            for pos in positions:
                pos = pos.split("=")
                moon["pos"][index[pos[0].strip()]] = int(pos[1].strip())
            # moon["pos_init"] = deepcopy(moon["pos"])
            moon["pos_init"] = moon["pos"][:]
            self.moons += [moon]
        return True

    def __reset(self):
        for moon_idx, moon in enumerate(self.moons):
            # self.moons[moon_idx]["pos"] = deepcopy(self.moons[moon_idx]["pos_init"])
            self.moons[moon_idx]["pos"] = self.moons[moon_idx]["pos_init"][:]
            self.moons[moon_idx]["vel"] = [0, 0, 0]
        return True

    def __add_values(self, val1, val2):
        for i, v in enumerate(val2):
            val1[i] += val2[i]
        return val1

    def motion_simulator(self, steps, axis=False):
        for i in range(steps):
            # temp_moons = deepcopy(self.moons)
            temp_moons = json.loads(json.dumps(self.moons))
            for moon_idx1, moon in enumerate(temp_moons):
                vel_diff = [0, 0, 0]
                for moon_idx2, moon2 in enumerate(temp_moons):
                    if moon_idx1 == moon_idx2:
                        continue
                    else:
                        if axis is False:
                            for j in range(3):
                                if moon["pos"][j] < moon2["pos"][j]:
                                    vel_diff[j] += 1
                                elif moon["pos"][j] > moon2["pos"][j]:
                                    vel_diff[j] -= 1
                        else:
                            if moon["pos"][axis] < moon2["pos"][axis]:
                                vel_diff[axis] += 1
                            elif moon["pos"][axis] > moon2["pos"][axis]:
                                vel_diff[axis] -= 1
                self.__add_values(self.moons[moon_idx1]["vel"], vel_diff)
                self.__add_values(self.moons[moon_idx1]["pos"], self.moons[moon_idx1]["vel"])

    def get_energy(self):
        energy = 0
        for moon in self.moons:
            pot = abs(moon["pos"][0]) + abs(moon["pos"][1]) + abs(moon["pos"][2])
            kin = abs(moon["vel"][0]) + abs(moon["vel"][1]) + abs(moon["vel"][2])
            energy += pot * kin
        return energy

    def calc_back_init_pos_steps(self):
        all_counts = []
        for axis in range(3):
            count = 0
            self.__reset()
            while True:
                self.motion_simulator(1, axis)
                count += 1
                if self.__is_at_init_pos_axis(axis):
                    break
            all_counts += [count]
        return self.get_group_lcm(all_counts)

    def __is_at_init_pos_axis(self, axis):
        for moon in self.moons:
            if moon["pos"][axis] != moon["pos_init"][axis]:
                return False
            if moon["vel"][axis] != 0:
                return False
        return True

    def __get_gcd(self, a, b):
        return b if a == 0 else self.__get_gcd(b % a, a)

    def __get_lcm(self, a, b):
        return (a * b) // self.__get_gcd(a, b)

    def get_group_lcm(self, group):
        a = group.pop(0)
        while len(group) > 0:
            b = group.pop(0)
            a = self.__get_lcm(a, b)
        return a
