from typing import List, Dict
from machines.space_object import SpaceObject


class OrbitMap:
    __map: List = []
    __routes: Dict = {}

    def __init__(self, map_data):
        self.__map = map(lambda line: line.split(")"), map_data)

        self.__routes = {}
        for item in self.__map:
            a: SpaceObject = SpaceObject.get_instance(item[0])
            b: SpaceObject = SpaceObject.get_instance(item[1])

            a.set_orbit_from(b)
            b.set_orbit_to(a)

            self.__routes[item[0]] = a
            self.__routes[item[1]] = b

    def __get_number(self, obj: SpaceObject):
        return 0 if obj.orbit_to is None else 1 + self.__get_number(obj.orbit_to)

    def get_transfer_num(self):
        total = 0
        for route_idx, route in self.__routes.items():
            total += self.__get_number(route)
        return total

    def __get_route(self, obj: SpaceObject):
        return obj.name if obj.orbit_to is None else f"{self.__get_route(obj.orbit_to)}){obj.name}"

    def get_minimal_transfer_between(self, from_name, to_name):
        route1 = self.__get_route(self.__routes[from_name]).split(")")
        route2 = self.__get_route(self.__routes[to_name]).split(")")

        while route1[0] == route2[0]:
            route1.pop(0)
            route2.pop(0)

        return len(route1) - 2 + len(route2) - 2 + 2
