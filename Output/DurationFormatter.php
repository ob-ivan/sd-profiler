<?php

namespace SD\Profiler\Output;

class DurationFormatter {
    public static function format(float $duration) {
        return round($duration * 1000) . 'ms';
    }
}
