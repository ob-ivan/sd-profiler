<?php

class SD_Profiler_OutputStrategy_FirePHP implements SD_Profiler_OutputStrategy_Interface {
    public function init($config) {
        $this->firephp()->setOptions([
            'includeLineNumbers' => false,
        ]);
        ob_start();
    }

    public function process(SD_Profiler_Frame $frame) {
        $this->fbRecursive($frame, 0);
        ob_flush();
    }

    private function fbRecursive(SD_Profiler_Frame $frame, int $depth) {
        $data = [];
        if ($frame->isStarted()) {
            $data = array_merge($data, [
                'inclusive' => $this->makeDuration($frame->getInclusiveDuration()),
                'exclusive' => $this->makeDuration($frame->getExclusiveDuration()),
            ]);
        }
        if ($frame->getVars()) {
            $data = array_merge($data, $frame->getVars());
        }
        $this->fb(
            $data,
            str_repeat('    ', $depth) . $frame->getLabel()
        );
        foreach ($frame->getChildren() as $child) {
            $this->fbRecursive($child, $depth + 1);
        }
    }

    private function fb($var, $label) {
        $this->firephp()->log($var, $label);
    }

    private function makeDuration(float $duration) {
        return SD_Profiler_OutputStrategy_DurationFormatter::format($duration);
    }

    private function firephp() {
        return FirePHP::getInstance(true);
    }
}
