<?php

class SD_Profiler_OutputStrategy_Append implements SD_Profiler_OutputStrategy_Interface {
    public function process(SD_Profiler_Frame $frame) {
        echo $this->makeOutput($frame, 0);
    }

    private makeOutput(SD_Profiler_Frame $frame, int $depth) {
        return "<table style='margin-left:{$depth}0px'>
            <tr>
                <td>{$frame->getLabel()}</td>
                <td>{$frame->getInclusiveDuration()}</td>
                <td>{$frame->getExclusiveDuration()}</td>
                <td>{$frame->getVars()}</td>
            </tr>
        </table>" . array_map(
            function ($frame) use ($depth) {
                return $this->makeOutput($frame, $depth + 1);
            },
            $frame->getChildren()
        )
    }
}
