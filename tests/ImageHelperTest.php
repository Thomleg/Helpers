<?php
/**
 * This file is part of Berlioz framework.
 *
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2019 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace Berlioz\Helpers\Tests;

use Berlioz\Helpers\ImageHelper;
use PHPUnit\Framework\TestCase;

class ImageHelperTest extends TestCase
{
    public function providerSizes(): array
    {
        return [
            [
                'params' => [
                    'width' => 100,
                    'height' => 100,
                    'newWidth' => 50,
                    'newHeight' => null,
                    'mode' => B_IMG_SIZE_RATIO
                ],
                'expected' => ['width' => 50, 'height' => 50]
            ],
            [
                'params' => [
                    'width' => 100,
                    'height' => 150,
                    'newWidth' => 50,
                    'newHeight' => null,
                    'mode' => B_IMG_SIZE_RATIO
                ],
                'expected' => ['width' => 50, 'height' => 75]
            ],
            [
                'params' => [
                    'width' => 150,
                    'height' => 100,
                    'newWidth' => 50,
                    'newHeight' => null,
                    'mode' => B_IMG_SIZE_RATIO
                ],
                'expected' => ['width' => 50, 'height' => 34]
            ],
            [
                'params' => [
                    'width' => 150,
                    'height' => 100,
                    'newWidth' => 50,
                    'newHeight' => 50,
                    'mode' => B_IMG_SIZE_LARGER_EDGE | B_IMG_SIZE_RATIO
                ],
                'expected' => ['width' => 75, 'height' => 50]
            ],
            [
                'params' => [
                    'width' => 100,
                    'height' => 150,
                    'newWidth' => 50,
                    'newHeight' => 50,
                    'mode' => B_IMG_SIZE_RATIO | B_IMG_SIZE_LARGER_EDGE
                ],
                'expected' => ['width' => 50, 'height' => 75]
            ],
            [
                'params' => [
                    'width' => 100,
                    'height' => 150,
                    'newWidth' => 50,
                    'newHeight' => 50,
                    'mode' => 8
                ],
                'expected' => ['width' => 50, 'height' => 50]
            ]
        ];
    }

    /**
     * @dataProvider providerSizes
     */
    public function testSize(array $params, array $expected)
    {
        $this->assertEquals($expected, call_user_func_array(sprintf('%s::%s', ImageHelper::class, 'size'), $params));
    }

    public function testGradientColor()
    {
        $this->assertEquals('#808080', ImageHelper::gradientColor('#ffffff', '#000000', 50));
        $this->assertEquals('#ff8080', ImageHelper::gradientColor('#ffffff', '#ff0000', 50));
        $this->assertEquals('#f78080', ImageHelper::gradientColor('#ffffff', '#ee0000', 50));
    }

    public function testResize()
    {
        $filename = __DIR__ . '/files/image.jpg';

        $resource = ImageHelper::resize($filename, 100);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(100, $size['width']);
        $this->assertEquals(50, $size['height']);

        $resource = ImageHelper::resize($filename, null, 100);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(200, $size['width']);
        $this->assertEquals(100, $size['height']);

        $resource = ImageHelper::resize($filename, 100, 100);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(100, $size['width']);
        $this->assertEquals(50, $size['height']);

        $resource = ImageHelper::resize($filename, 100, 100, B_IMG_RESIZE_COVER);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(100, $size['width']);
        $this->assertEquals(100, $size['height']);
    }

    public function testResizeSupport()
    {
        $filename = __DIR__ . '/files/image.jpg';

        $resource = ImageHelper::resizeSupport($filename, 100, 100);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(100, $size['width']);
        $this->assertEquals(100, $size['height']);

        $resource = ImageHelper::resizeSupport($filename, 1024, 32);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(1024, $size['width']);
        $this->assertEquals(32, $size['height']);

        $resource = ImageHelper::resizeSupport($filename, null, 32);
        $size = ImageHelper::getImageSize($resource);
        $this->assertEquals(1024, $size['width']);
        $this->assertEquals(32, $size['height']);
    }
}
