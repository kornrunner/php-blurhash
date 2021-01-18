<?php

namespace kornrunner\Blurhash;

use PHPUnit\Framework\TestCase;

class DCTest extends TestCase {

    public function testEncode () {
        $this->assertSame (65793, DC::encode ([0, 0, 0], 1));
        $this->assertSame (16777215, DC::encode ([255, 255, 255], 1));
        $this->assertSame (65793, DC::encode ([-1, -1, -1], 1));
    }

    public function testDecode () {
        $this->assertSame ([0.0, 0.004024717018496307, 0.1301364766903643], DC::decode (3429, 1));
        $this->assertSame ([0.0, 0.010329823029626936, 0.5906188409193369], DC::decode (6858, 1));
        $this->assertSame ([0.0, 0.0, 0.0], DC::decode (0, 1));
    }
}