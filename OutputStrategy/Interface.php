<?php

interface SD_Profiler_OutputStrategy_Interface {
    public function process(SD_Profiler_Frame $frame);
}
