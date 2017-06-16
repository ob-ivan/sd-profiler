<?php

class SD_Profiler_OutputStrategy_Append implements SD_Profiler_OutputStrategy_Interface {
    public function init($config) {
    }

    public function process(SD_Profiler_Frame $frame) {
        echo "<table class='profiler'>{$this->makeOutput($frame, 0)}</table>";
    }

    private function makeOutput(SD_Profiler_Frame $frame, int $depth) {
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

    private function makeDuration(float $duration) {
        return SD_Profiler_OutputStrategy_DurationFormatter::format($duration);
    }

    private function makeVars(array $vars) {
        return print_r($vars, true);
    }
}
