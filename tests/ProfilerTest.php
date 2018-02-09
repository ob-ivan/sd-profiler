<?php
namespace tests;

use SD\Profiler\Profiler;
use PHPUnit\Framework\TestCase;

class ProfilerTest extends TestCase {
    public function testDispatch() {
        $profiler = new Profiler();
        $this->assertNull($profiler->dispatch(), 'Dispatching an uninitialized profiler MUST NOT trigger any errors');
    }
}
