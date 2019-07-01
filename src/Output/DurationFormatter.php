<?php

namespace SD\Profiler\Output;

class DurationFormatter {
    /**
     * @param float $duration
     * @return string
     */
    public static function format($duration) {
        return round($duration * 1000) . 'ms';
    }
}
