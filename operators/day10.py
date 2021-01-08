from utilities.operator import OperatorBase
from machines.asteroid_detector import AsteroidDetector


class Operator(OperatorBase):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data)

    def __part1(self, map_data):
        detector = AsteroidDetector(map_data)
        detector.calc_observable_count()
        best_location = detector.get_best_location()
        ret = [f"coords: {best_location['coordinate']}", f"count: {best_location['count']}"]
        return "\n".join(ret)

    def __part2(self, map_data):
        detector = AsteroidDetector(map_data)
        detector.calc_observable_count()
        best_location = detector.get_best_location()
        ret = [f"coords: {best_location['coordinate']}", f"count: {best_location['count']}"]

        vape_orders = detector.get_vape_order(best_location["coordinate"])
        coords = vape_orders[199]
        ret += [100 * coords[0] + coords[1]]

        return "\n".join([str(r) for r in ret])
