# php-blurhash [![Tests](https://github.com/kornrunner/php-blurhash/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/kornrunner/php-blurhash/actions/workflows/tests.yml) [![Coverage Status](https://coveralls.io/repos/github/kornrunner/php-blurhash/badge.svg?branch=master)](https://coveralls.io/github/kornrunner/php-blurhash?branch=master) [![Latest Stable Version](https://poser.pugx.org/kornrunner/blurhash/v/stable)](https://packagist.org/packages/kornrunner/blurhash)


A pure PHP implementation of [Blurhash](https://github.com/woltapp/blurhash). The API is stable, however the hashing function in either direction may not be.

Blurhash is an algorithm written by [Dag Ågren](https://github.com/DagAgren) for [Wolt (woltapp/blurhash)](https://github.com/woltapp/blurhash) that encodes an image into a short (~20-30 byte) ASCII string. When you decode the string back into an image, you get a gradient of colors that represent the original image. This can be useful for scenarios where you want an image placeholder before loading, or even to censor the contents of an image [a la Mastodon](https://blog.joinmastodon.org/2019/05/improving-support-for-adult-content-on-mastodon/).

## Installation


```sh
$ composer require kornrunner/blurhash
```

## Usage

Encoding an image to blurhash expects two-dimensional array of colors of image pixels, sample code:

```php
<?php

require_once 'vendor/autoload.php';

use kornrunner\Blurhash\Blurhash;

$file  = 'test/data/img1.jpg';
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

$components_x = 4;
$components_y = 3;
$blurhash = Blurhash::encode($pixels, $components_x, $components_y);
// LEHV9uae2yk8pyo0adR*.7kCMdnj
```

For decoding of blurhash people will likely go for some other implementation ([JavaScript/TypeScript](https://github.com/woltapp/blurhash/tree/master/TypeScript)).
PHP decoder returns a pixel array that can be used to generate the image:

```php
<?php

require_once 'vendor/autoload.php';

use kornrunner\Blurhash\Blurhash;

$blurhash = 'LEHV6nWB2yk8pyo0adR*.7kCMdnj';
$width    = 269;
$height   = 173;

$pixels = Blurhash::decode($blurhash, $width, $height);
$image  = imagecreatetruecolor($width, $height);
for ($y = 0; $y < $height; ++$y) {
    for ($x = 0; $x < $width; ++$x) {
        [$r, $g, $b] = $pixels[$y][$x];
        imagesetpixel($image, $x, $y, imagecolorallocate($image, $r, $g, $b));
    }
}
imagepng($image, 'blurhash.png');
```

## Contributing

Issues, feature requests or improvements welcome!

## Licence

This project is licensed under the [MIT License](LICENSE).

## Stargazing

[![Star History Chart](https://api.star-history.com/svg?repos=kornrunner/php-blurhash&type=Date)](https://star-history.com/#kornrunner/php-blurhash&Date)
