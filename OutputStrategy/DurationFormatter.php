<?php

class SD_Profiler_OutputStrategy_DurationFormatter {
    public static function format(float $duration) {
        return round($duration * 1000) . 'ms';
    }
}
