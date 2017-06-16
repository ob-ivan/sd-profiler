<?php

class SD_Profiler_OutputStrategy_FirePHP implements SD_Profiler_OutputStrategy_Interface {
    public function init($config) {
        ob_start();
    }

    public function process(SD_Profiler_Frame $frame) {
        $this->fbRecursive($frame, 0);
        ob_flush();
    }

    private function fbRecursive(SD_Profiler_Frame $frame, int $depth) {
        $this->fb(
            [
                'inclusive' => $this->makeDuration($frame->getInclusiveDuration()),
                'exclusive' => $this->makeDuration($frame->getExclusiveDuration()),
                'vars' => $frame->getVars()
            ],
            str_repeat('    ', $depth) . $frame->getLabel()
        );
        foreach ($frame->getChildren() as $child) {
            $this->fbRecursive($child, $depth + 1);
        }
    }

    private function fb($var, $label) {
        FirePHP::getInstance(true)->log($var, $label);
    }

    private function makeDuration(float $duration) {
        return SD_Profiler_OutputStrategy_DurationFormatter::format($duration);
    }
}
