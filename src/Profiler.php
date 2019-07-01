<?php
namespace SD\Profiler;

use SD\Profiler\Output\AppendOutput;
use SD\Profiler\Output\FirePhpOutput;
use SD\Profiler\Output\OutputInterface;

class Profiler
{
    private static $instance;
    private $isEnabled = false;
    private $config = [];
    private $frameRoot;
    private $frameStack = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(array $config)
    {
        $this->isEnabled = true;
        $this->config = $config;
        foreach ($this->config as $strategyName => $strategyConfig) {
            $outputStrategy = $this->getOutputStrategy($strategyName);
            $outputStrategy->init($strategyConfig);
        }
        $this->in('root');
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }


    /**
     * @param string $label
     * @param mixed ...$vars
     */
    public function log($label, $vars = null)
    {
        if (!$this->isEnabled) {
            return;
        }

        // BEGIN PHP 5.5 compatibility
        $vars = func_get_args();
        array_shift($vars);
        // END PHP 5.5 compatibility

        $frame = new Frame($label, $vars);
        end($this->frameStack)->addChildFrame($frame);
    }


    /**
     * @param string $label
     * @param mixed ...$vars
     */
    public function in($label, $vars = null)
    {
        if (!$this->isEnabled) {
            return;
        }

        // BEGIN PHP 5.5 compatibility
        $vars = func_get_args();
        array_shift($vars);
        // END PHP 5.5 compatibility

        $frame = new Frame($label, $vars);
        $frame->in();
        if (empty($this->frameStack)) {
            $this->frameRoot = $frame;
            $this->frameStack[] = $this->frameRoot;
        } else {
            end($this->frameStack)->enterChildFrame($frame);
            $this->frameStack[] = $frame;
        }
    }


    /**
     * @param string|null $label
     */
    public function out($label = null)
    {
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

    public function dispatch()
    {
        while ($this->frameStack) {
            $this->out();
        }
        foreach ($this->config as $strategyName => $strategyConfig) {
            $outputStrategy = $this->getOutputStrategy($strategyName);
            $outputStrategy->process($this->frameRoot);
        }
    }


    /**
     * @param string $name
     * @return OutputInterface
     */
    private function getOutputStrategy($name)
    {
        switch ($name) {
            case 'append': return new AppendOutput();
            case 'firephp': return new FirePhpOutput();
        }
    }
}
