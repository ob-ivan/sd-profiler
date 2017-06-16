<?php

class SD_Profiler_Profiler {
    private static $instance;
    private $isEnabled = false;
    private $config;
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
        $this->config = $config;
        foreach ($this->config as $strategyName => $strategyConfig) {
            $outputStrategy = $this->getOutputStrategy($strategyName);
            $outputStrategy->init($strategyConfig);
        }
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

    public function out(string $label = null) {
        if (!$this->isEnabled) {
            return;
        }
        $frame = array_pop($this->frameStack);
        if ($label && $frame->getLabel() !== $label) {
            // TODO
        }
        $frame->out();
        if ($this->frameStack) {
            end($this->frameStack)->exitChildFrame($frame);
        }
    }

    public function dispatch() {
        while ($this->frameStack) {
            $this->out();
        }
        foreach ($this->config as $strategyName => $strategyConfig) {
            $outputStrategy = $this->getOutputStrategy($strategyName);
            $outputStrategy->process($this->frameRoot);
        }
    }

    private function getOutputStrategy(string $name): SD_Profiler_OutputStrategy_Interface {
        switch ($name) {
            case 'append': return new SD_Profiler_OutputStrategy_Append();
            case 'firephp': return new SD_Profiler_OutputStrategy_FirePHP();
        }
    }
}
