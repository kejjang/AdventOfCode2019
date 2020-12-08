from typing import Dict, List


class SpaceObject:
    __instacnces: Dict = {}
    name: str
    orbit_to: "SpaceObject"
    orbit_from: List["SpaceObject"]

    def __init__(self, name):
        self.name = name
        self.orbit_to = None
        self.orbit_from = []

    def set_orbit_to(self, orbit_to: "SpaceObject"):
        self.orbit_to = orbit_to

    def set_orbit_from(self, orbit_from: "SpaceObject"):
        self.orbit_from += [orbit_from]

    @classmethod
    def get_instance(cls, name):
        if name not in cls.__instacnces:
            cls.__instacnces[name] = SpaceObject(name)
        return cls.__instacnces[name]
