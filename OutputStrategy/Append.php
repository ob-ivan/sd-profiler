<?php

class SD_Profiler_OutputStrategy_Append implements SD_Profiler_OutputStrategy_Interface {
    public function process(SD_Profiler_Frame $frame) {
        echo $this->makeOutput($frame, 0);
    }

    private function makeOutput(SD_Profiler_Frame $frame, int $depth) {
        return "<table style='margin-left:{$depth}0px'>
            <tr>
                <td>{$frame->getLabel()}</td>
                <td>{$this->makeDuration($frame->getInclusiveDuration())}</td>
                <td>{$this->makeDuration($frame->getExclusiveDuration())}</td>
                <td>{$this->makeVars($frame->getVars())}</td>
            </tr>
        </table>" . implode('', array_map(
            function ($frame) use ($depth) {
                return $this->makeOutput($frame, $depth + 1);
            },
            $frame->getChildren()
        ));
    }

    private function makeDuration(float $duration) {
        return round($duration * 1000) . 'ms';
    }

    private function makeVars(array $vars) {
        return print_r($vars, true);
    }
}
