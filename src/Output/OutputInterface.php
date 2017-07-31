<?php

namespace SD\Profiler\Output;

use SD\Profiler\Frame;

interface OutputInterface {
    public function init($config);
    public function process(Frame $frame);
}
