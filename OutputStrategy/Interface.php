<?php

interface SD_Profiler_OutputStrategy_Interface {
    public function init($config);
    public function process(SD_Profiler_Frame $frame);
}
