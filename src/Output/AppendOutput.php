<?php

namespace SD\Profiler\Output;

use SD\Profiler\Frame;

class AppendOutput implements OutputInterface {
    public function init($config) {
    }

    public function process(Frame $frame) {
        echo "<table class='profiler'>{$this->makeOutput($frame, 0)}</table>";
    }


    /**
     * @param Frame $frame
     * @param int $depth
     * @return string
     */
    private function makeOutput(Frame $frame, $depth) {
        return "<tr>
            <td style='padding-left: {$depth}0px'>{$frame->getLabel()}</td>
            <td>{$this->makeDuration($frame->getInclusiveDuration())}</td>
            <td>{$this->makeDuration($frame->getExclusiveDuration())}</td>
            <td>{$this->makeVars($frame->getVars())}</td>
        </tr>" . implode('', array_map(
            function ($frame) use ($depth) {
                return $this->makeOutput($frame, $depth + 1);
            },
            $frame->getChildren()
        ));
    }


    /**
     * @param float $duration
     * @return string
     */
    private function makeDuration($duration) {
        return DurationFormatter::format($duration);
    }

    private function makeVars(array $vars) {
        return print_r($vars, true);
    }
}
