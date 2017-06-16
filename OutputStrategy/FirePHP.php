<?php

class SD_Profiler_OutputStrategy_FirePHP implements SD_Profiler_OutputStrategy_Interface {
    public function process(SD_Profiler_Frame $frame) {
        $this->fb($frame, 0)
    }

    private function fb(SD_Profiler_Frame $frame, int $depth) {
        fb(
            [
                'inclusive' => $this->makeDuration($frame->getInclusiveDuration()),
                'exclusive' => $this->makeDuration($frame->getExclusiveDuration()),
                'vars' => $frame->getVars()
            ],
            str_repeat('    ', $depth) . $frame->getLabel()
        );
        foreach ($frame->getChildren() as $child) {
            $this->fb($child, $depth + 1);
        }
    }

    private function makeDuration(float $duration) {
        return SD_Profiler_OutputStrategy_DurationFormatter::format($duration);
    }
}
