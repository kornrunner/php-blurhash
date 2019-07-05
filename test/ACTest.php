<?php

namespace kornrunner\Blurhash;

use PHPUnit\Framework\TestCase;

class ACTest extends TestCase {

    public function testEncode () {
        $this->assertSame (3429.0, AC::encode ([0, 0, 0], 1));
        $this->assertSame (6858.0, AC::encode ([255, 255, 255], 1));
        $this->assertSame (0.0, AC::encode ([-1, -1, -1], 1));
    }

    public function testDecode () {
        $this->assertSame ([0.0, 0.0, 0.0], AC::decode (3429, 1));
        $this->assertSame ([1.0, 1.0, 1.0], AC::decode (6858, 1));
        $this->assertSame ([-1.0, -1.0, -1.0], AC::decode (0, 1));
    }
}