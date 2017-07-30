<?php

namespace SD\Profiler\Output;

interface OutputInterface {
    public function init($config);
    public function process(SD\Profiler\Frame $frame);
}
