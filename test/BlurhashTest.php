<?php

namespace kornrunner\Blurhash;

use PHPUnit\Framework\TestCase;

use InvalidArgumentException;

class BlurhashTest extends TestCase {

    public function testEncodeThrows() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('x and y component counts must be between 1 and 9 inclusive.');
        Blurhash::encode([], -1);
        Blurhash::encode([], -1, 5);
        Blurhash::encode([], -1, 17);
    }

    public function testDecodeThrowsShortHash() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Blurhash string must be at least 6 characters');
        Blurhash::decode('', 0, 0);
    }

    /**
     * @dataProvider hashes
     */
    public function testDecodeThrowsWrongSize ($hash, $width, $height) {
        $this->expectException(InvalidArgumentException::class);
        $length = strlen ($hash);
        $provided = $length - 1;
        $this->expectExceptionMessage("Blurhash length mismatch: length is {$provided} but it should be {$length}");
        Blurhash::decode(substr ($hash, 0, -1), $width, $height);
    }

    /**
     * @dataProvider hashes
     */
    public function testDecode($hash, $width, $height) {
        $decoded = Blurhash::decode($hash, $width, $height);

        $filename = sprintf ('%s/data/%s.json', __DIR__, substr($hash, 0, 4));
        $data = file_get_contents($filename);
        $this->assertIsArray($decoded);
        $this->assertSame (json_decode ($data, true), $decoded);
    }

    public function hashes(): array {
        return [
            ['LEHV9uae2yk8pyo0adR*.7kCMdnj', 269, 173],
            ['LGFO~6Yk^6#M@-5c,1Ex@@or[j6o', 301, 193],
            ['L6Pj42nh.AyE?vt7t7R**0o#DgR4', 242, 172],
            ['LKO2?V%2Tw=^]~RBVZRi};RPxuwH', 187, 120],
        ];
    }

    /**
     * @dataProvider imageFiles
     */
    public function testEncode($image, $hash) {
        $pixels = $this->getImagePixels($image);
        $this->assertSame($hash, Blurhash::encode ($pixels, 4, 3));
    }

    public function imageFiles(): array {
        return [
            [__DIR__ . '/data/img1.jpg', 'LEHV9uae2yk8pyo0adR*.7kCMdnj'],
            [__DIR__ . '/data/img2.jpg', 'LGFO~6Yk^6#M@-5c,1Ex@@or[j6o'],
            [__DIR__ . '/data/img3.jpg', 'L6Pj42nh.AyE?vt7t7R**0o#DgR4'],
            [__DIR__ . '/data/img4.jpg', 'LKO2?V%2Tw=^]~RBVZRi};RPxuwH'],
        ];
    }

    private function getImagePixels($file) {
        $image = imagecreatefromstring(file_get_contents($file));
        $width = imagesx($image);
        $height = imagesy($image);

        $pixels = [];
        for ($y = 0; $y < $height; ++$y) {
            $row = [];
            for ($x = 0; $x < $width; ++$x) {
                $index = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $index);

                $row[] = [$colors['red'], $colors['green'], $colors['blue']];
            }

            $pixels[] = $row;
        }
        return $pixels;
    }
}
