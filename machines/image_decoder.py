from typing import Dict


class ImageDecoder:
    __debug: bool = False
    __width: int = None
    __height: int = None
    __img_code: str = None
    __layers: Dict = None
    __pic: Dict = None
    __pic_pos: int = None

    def __init__(self, width: int = None, height: int = None, img_code: str = None, debug: bool = False):
        if width is not None:
            self.set_width(width)
        if height is not None:
            self.set_height(height)
        if img_code is not None:
            self.set_img_code(img_code)

        self.set_debug(debug)

        if self.check_is_valid_inputs():
            self.set_image_layers()

    def set_width(self, width: int):
        self.__width = width
        if self.check_is_valid_inputs():
            self.set_image_layers()
        return True

    def set_height(self, height: int):
        self.__height = height
        if self.check_is_valid_inputs():
            self.set_image_layers()
        return True

    def set_img_code(self, img_code: str):
        self.__img_code = img_code
        if self.check_is_valid_inputs():
            self.set_image_layers()
        return True

    def set_debug(self, debug: bool = False):
        self.__debug = debug
        return True

    def check_is_valid_inputs(self):
        return True if (self.__width is not None and self.__height is not None and self.__img_code is not None) else False

    def set_image_layers(self):
        layer = 0
        self.__layers = {}
        self.__pic = {}
        self.__pic_pos = 0

        img_code = list(self.__img_code.replace(" ", ""))
        debug_info = ""

        for pos in range(len(img_code)):
            if pos > 0 and pos % self.__width == 0:
                debug_info += "\n"
            if pos % (self.__width * self.__height) == 0:
                if pos > 0:
                    layer += 1
                debug_info = f"\nLayer {layer}:\n"
                self.__layers[layer] = [0, 0, 0]
            self.__layers[layer][int(img_code[pos])] += 1
            debug_info += img_code[pos]

            self.__pic_pos = pos % (self.__width * self.__height)
            if img_code[pos] != "2" and self.__pic_pos not in self.__pic:
                self.__pic[self.__pic_pos] = img_code[pos]

        debug_info += "\n"

        if self.__debug:
            print(debug_info)

    @staticmethod
    def __sort_by_fewest_0(a, b):
        return 0 if a[0] == b[0] else -1 if a[0] < b[0] else 1

    def get_fewest_0_layer(self):
        self.__layers = sorted(self.__layers.items(), key=lambda item: item[1][0])
        return self.__layers[0][1]

    def __set_pixel(self, pixel, raw=False):
        return pixel if raw else "\033[47m \033[0m" if pixel == "1" else " "

    def drawPic(self, ret=False, raw=False):
        output = ""
        output += "\n"

        for pos in range(len(self.__pic)):
            if pos > 0 and pos % self.__width == 0:
                output += "\n"
            output += self.__set_pixel(self.__pic[pos], raw)
        output += "\n"

        if ret:
            return output.strip()
        else:
            print(output)
