from typing import List
from machines.intcode import Intcode as Amplifier


class AmplifierController:
    __amps = []
    __possible_phases = []
    __signals = []
    __intcode = None

    def __init__(self, intcode=False, possible_phases=False):
        if intcode is not False:
            self.set_intcode(intcode)
        if possible_phases is not False:
            self.set_possible_phases(possible_phases)

    def set_intcode(self, intcode):
        self.__intcode = intcode
        return True

    def set_possible_phases(self, possible_phases):
        self.__possible_phases = possible_phases
        return True

    def run(self):
        self.__signals = []
        for test_phases in self.__possible_phases:
            self.__amps = []
            for i in range(5):
                self.__amps += [Amplifier(self.__intcode)]
            self.__signals += [self.__calc_signal(self.__amps, test_phases)]

        return max(self.__signals)

    def __calc_signal(self, amps: List[Amplifier], phases):
        signal = 0

        for idx, amp in enumerate(amps):
            phase = phases[idx]
            amp.set_inputs(phase)

        amp_idx = 0
        status = amps[amp_idx].get_status()

        while not (status == 3 and amp_idx == 0):
            amps[amp_idx].set_inputs(signal)
            status = amps[amp_idx].get_status()

            while status == 1:
                op, status = amps[amp_idx].calc()

            signal = amps[amp_idx].get_signal()
            amp_idx = (amp_idx + 1) % 5

        return int(signal)
