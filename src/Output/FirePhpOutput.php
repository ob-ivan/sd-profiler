<?php

namespace SD\Profiler\Output;

use SD\Profiler\Frame;

class FirePhpOutput implements OutputInterface {
    public function init($config) {
        $this->firephp()->setOptions([
            'includeLineNumbers' => false,
        ]);
        ob_start();
    }

    public function process(Frame $frame) {
        $this->fbRecursive($frame, 0);
        ob_flush();
    }


    /**
     * @param Frame $frame
     * @param int $depth
     */
    private function fbRecursive(Frame $frame, $depth) {
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
        $indentedLabel = str_repeat('    ', $depth) . $frame->getLabel();
        if ($data) {
            $this->fb($data, $indentedLabel);
        } else {
            $this->fb($indentedLabel);
        }
        foreach ($frame->getChildren() as $child) {
            $this->fbRecursive($child, $depth + 1);
        }
    }

    private function fb($var, $label = null) {
        $this->firephp()->log($var, $label);
    }


    /**
     * @param float $duration
     * @return string
     */
    private function makeDuration($duration) {
        return DurationFormatter::format($duration);
    }

    private function firephp() {
        return \FirePHP::getInstance(true);
    }
}
