from utilities.operator import Base
from machines.phase_utility import PhaseUtility as PossiblePhaseGenerator
from machines.amplifier_controller import AmplifierController


class Operator(Base):
    def exec(self, part: int = 1):
        return (parts := {1: self.__part1, 2: self.__part2}).get(part if part in parts else 1)(self.data[0])

    def __part1(self, intcode):
        phase_mode = PossiblePhaseGenerator.MODE_GENERAL
        possible_phases = PossiblePhaseGenerator.gen(phase_mode)

        amp_ctrl = AmplifierController(intcode, possible_phases)
        max_signal = amp_ctrl.run()

        return max_signal

    def __part2(self, intcode):
        phase_mode = PossiblePhaseGenerator.MODE_FEEDBACK
        possible_phases = PossiblePhaseGenerator.gen(phase_mode)

        amp_ctrl = AmplifierController(intcode, possible_phases)
        max_signal = amp_ctrl.run()

        return max_signal
