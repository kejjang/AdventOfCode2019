from typing import List, Dict


class AsteroidDetector:
    __map: List[List[str]] = None
    asteroids: Dict = {}

    def __init__(self, map_data: List[str] = None):
        if map_data is not None:
            self.set_map(map_data)

    def set_map(self, map_data=None):
        if map_data is not None:
            result = self.__parse_map(map_data)
            if result:
                return self.__parse_coordinates()
            else:
                return False
        else:
            return False

    def __parse_map(self, map_data):
        self.__map = [list(line) for line in map_data]
        return True if len(self.__map) > 0 else False

    def __gen_key(self, x, y, reverse=False):
        if reverse:
            x, y = y, x

        prefix_x = "N" if x < 0 else "P"
        prefix_y = "N" if y < 0 else "P"

        key = f"{prefix_x}{abs(x)}{prefix_y}{abs(y)}"
        return key

    def __gen_quadrant_key(self, x, y, reverse=False):
        if reverse:
            x, y = y, x

        key_x = "Z" if x == 0 else "N" if x < 0 else "P"
        key_y = "Z" if y == 0 else "N" if y < 0 else "P"

        key = f"{key_x}{key_y}"
        return key

    def __parse_coordinates(self):
        self.asteroids = {}
        for y, row in enumerate(self.__map):
            for x, obj in enumerate(row):
                if obj == "#":
                    key = self.__gen_key(x, y)
                    self.asteroids[key] = {"coordinate": [x, y], "observable": []}
        return True if len(self.asteroids) > 0 else False

    def get_asteroids(self, coords=False):
        if coords is not False:
            if type(coords) == str:
                coords = [int(c) for c in coords.split(",")]
            return self.asteroids[self.__gen_key(*coords)]
        else:
            return self.asteroids

    def calc_observable_count(self):
        for idx1, astro1 in self.asteroids.items():
            self.asteroids[idx1]["observable"] = []
            x1, y1 = astro1["coordinate"]
            for idx2, astro2 in self.asteroids.items():
                if idx1 == idx2:
                    continue
                x2, y2 = astro2["coordinate"]
                if self.__clear_between_us(x1, y1, x2, y2):
                    self.asteroids[idx1]["observable"] += [[x2, y2]]

    def __gen_params(self, x1, y1, x2, y2):
        x_diff = x2 - x1
        y_diff = y2 - y1
        reverse = False

        if abs(x_diff) < abs(y_diff):
            x1, y1, x2, y2, x_diff, y_diff = y1, x1, y2, x2, y_diff, x_diff
            reverse = True

        if x_diff < 0:
            x1, x2, y1, y2 = x2, x1, y2, y1
            x_diff *= -1
            y_diff *= -1

        return [[x1, x2], y1, (y_diff / x_diff), reverse]

    def __clear_between_us(self, x1, y1, x2, y2):
        check_coords = []
        key1_range, key2_base, seg, reverse = self.__gen_params(x1, y1, x2, y2)
        for key1 in range(key1_range[0], key1_range[1] + 1):
            steps = key1 - key1_range[0]
            key2 = key2_base + steps * seg
            key2 = int(key2) if key2.is_integer() else key2
            if str(key2).isdecimal():
                check_coords += [self.__gen_key(key1, key2, reverse)]

        if len(check_coords) > 0:
            check_coords.pop(-1)
        if len(check_coords) > 0:
            check_coords.pop(0)

        clear = True
        all_keys = self.asteroids.keys()
        if len(list(set(all_keys) & set(check_coords))) > 0:
            clear = False
        return clear

    def get_best_location(self):
        aster = self.asteroids.values()
        aster = sorted(aster, key=lambda item: len(item["observable"]), reverse=True)
        return {"coordinate": ",".join([str(c) for c in aster[0]["coordinate"]]), "count": len(aster[0]["observable"])}

    def __vape_asteroids(self, asters):
        for aster in asters:
            key = self.__gen_key(aster[0], aster[1])
            del self.asteroids[key]

    def get_vape_order(self, coords=False):
        if coords is False:
            return False

        orders = []
        asteroids_data_backup = self.asteroids.copy()

        while len(self.asteroids) > 1:
            round_orders = self.__get_vape_order_round(coords)
            orders += round_orders
            self.__vape_asteroids(round_orders)
            self.calc_observable_count()

        self.asteroids = asteroids_data_backup.copy()
        return orders

    def __get_vape_order_round(self, coords=False):
        if coords is False:
            return False

        orders = []
        # divisions = [
        #     [], # up
        #     [], # 1st quadrant
        #     [], # right
        #     [], # 4th quadrant
        #     [], # down
        #     [], # 3rd quadrant
        #     [], # left
        #     []  # 2nd quadrant
        # ]
        divisions = [[], [], [], [], [], [], [], []]

        asteroid_data = self.get_asteroids(coords)

        base_x = asteroid_data["coordinate"][0]
        base_y = asteroid_data["coordinate"][1]

        for aster in asteroid_data["observable"]:
            relative_x = aster[0] - base_x
            relative_y = aster[1] - base_y

            quadrant_key = self.__gen_quadrant_key(relative_x, relative_y)

            if quadrant_key == "ZN":
                divisions[0] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_y)}]
            elif quadrant_key == "PN":
                divisions[1] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_y / relative_x)}]
            elif quadrant_key == "PZ":
                divisions[2] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_x)}]
            elif quadrant_key == "PP":
                divisions[3] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_x / relative_y)}]
            elif quadrant_key == "ZP":
                divisions[4] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_y)}]
            elif quadrant_key == "NP":
                divisions[5] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_y / relative_x)}]
            elif quadrant_key == "NZ":
                divisions[6] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_x)}]
            elif quadrant_key == "NN":
                divisions[7] += [{"coords": [relative_x, relative_y], "angle_factor": abs(relative_x / relative_y)}]

        for div in divisions:
            div = sorted(div, key=lambda item: item["angle_factor"], reverse=True)
            div = list(map(lambda aster: [aster["coords"][0] + base_x, aster["coords"][1] + base_y], div))
            orders += div

        return orders
