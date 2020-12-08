from utilities.operator import Base
from machines.image_decoder import ImageDecoder


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, img_code):
        w = 25
        h = 6
        debug = False

        decoder = ImageDecoder(w, h, img_code, debug)
        layer = decoder.get_fewest_0_layer()

        return layer[1] * layer[2]

    def __part2(self, img_code):
        w = 25
        h = 6
        debug = False

        decoder = ImageDecoder(w, h, img_code, debug)
        return decoder.drawPic(ret=True)
