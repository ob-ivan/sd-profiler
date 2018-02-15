<?php
namespace tests;

use SD\Profiler\Profiler;
use PHPUnit\Framework\TestCase;

class ProfilerTest extends TestCase
{
    public function testDispatch()
    {
        $profiler = new Profiler();
        $this->assertNull($profiler->dispatch(), 'Dispatching an uninitialized profiler MUST NOT trigger any errors');
    }

    public function testIsEnabledFalse()
    {
        $profiler = new Profiler();
        $this->assertFalse($profiler->isEnabled(), 'MUST NOT be enabled by default');
    }

    public function testIsEnabledTrue()
    {
        $profiler = new Profiler();
        $profiler->init([]);
        $this->assertTrue($profiler->isEnabled(), 'MUST be enabled after init');
    }
}
