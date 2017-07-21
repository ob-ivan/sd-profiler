<?php

class SD_Profiler_Frame {
    private $label;
    private $vars;
    private $inclusiveStart;
    private $inclusiveEnd;
    private $inclusiveDuration = 0;
    private $exclusiveStart;
    private $exclusiveEnd;
    private $exclusiveDuration = 0;
    private $children = [];

    public function __construct(string $label, array $vars) {
        $this->label = $label;
        $this->vars = $vars;
    }

    public function isStarted() {
        return (bool)$this->inclusiveStart;
    }

    public function in() {
        $now = microtime(true);
        $this->inclusiveStart = $now;
        $this->exclusiveStart = $now;
    }

    public function out() {
        $now = microtime(true);
        $this->exclusiveEnd = $now;
        $this->exclusiveDuration += $this->exclusiveEnd - $this->exclusiveStart;
        $this->inclusiveEnd = $now;
        $this->inclusiveDuration += $this->inclusiveEnd - $this->inclusiveStart;
    }

    public function addChildFrame(self $child) {
        $this->children[] = $child;
    }

    public function enterChildFrame(self $child) {
        $this->exclusiveEnd = $child->exclusiveStart;
        $this->exclusiveDuration += $this->exclusiveEnd - $this->exclusiveStart;
        $this->children[] = $child;
    }

    public function exitChildFrame(self $child) {
        $this->exclusiveStart = $child->exclusiveEnd;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getVars() {
        return $this->vars;
    }

    public function getInclusiveDuration() {
        return $this->inclusiveDuration;
    }

    public function getExclusiveDuration() {
        return $this->exclusiveDuration;
    }

    public function getChildren() {
        return $this->children;
    }
}
