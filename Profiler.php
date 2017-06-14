<?php

class SD_Profiler_Profiler {
    private static $instance;
    private $isEnabled = false;
    private $frameRoot;
    private $frameStack = [];

    private function __construct() {
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(array $config) {
        $this->isEnabled = true;
        register_shutdown_function(function () use ($config) {
            foreach ($config as $strategyName => $strategyConfig) {
                $outputStrategy = $this->getOutputStrategy($strategyName, $strategyConfig);
                $outputStrategy->process($this->frameRoot);
            }
        });
        $this->in('root');
    }

    public function in(string $label, ...$vars) {
        if (!$this->isEnabled) {
            return;
        }
        $frame = new SD_Profiler_Frame($label, $vars);
        $frame->in();
        if (empty($this->frameStack)) {
            $this->frameRoot = $frame;
            $this->frameStack[] = $this->frameRoot;
        } else {
            end($this->frameStack)->enterChildFrame($frame);
            $this->frameStack[] = $frame;
        }
    }

    public function out(string $label) {
        if (!$this->isEnabled) {
            return;
        }
        $frame = array_pop($this->frameStack);
        if ($frame->getLabel() !== $label) {
            // TODO
        }
        $frame->out();
        end($this->frameStack)->exitChildFrame($frame);
    }

    private function getOutputStrategy(string $name, $config): SD_Profiler_OutputStrategy_Interface {
        switch ($name) {
            case 'append': return new SD_Profiler_OutputStrategy_Append($config);
        }
    }
}
