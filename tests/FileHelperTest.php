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

use Berlioz\Helpers\FileHelper;
use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase
{
    public function testHumanFileSize()
    {
        $this->assertEquals('foo', FileHelper::humanFileSize('foo'));
        $this->assertEquals('200 bytes', FileHelper::humanFileSize(200));
        $this->assertEquals('1 KB', FileHelper::humanFileSize(1024));
        $this->assertEquals('976.56 KB', FileHelper::humanFileSize(1000000));
        $this->assertEquals('976.563 KB', FileHelper::humanFileSize(1000000, 3));
        $this->assertEquals('977 KB', FileHelper::humanFileSize(1000000, 0));
        $this->assertEquals('2 MB', FileHelper::humanFileSize(2097152));
        $this->assertEquals('1 GB', FileHelper::humanFileSize(pow(1024, 3)));
        $this->assertEquals('1 TB', FileHelper::humanFileSize(pow(1024, 4)));
        $this->assertEquals('1 PB', FileHelper::humanFileSize(pow(1024, 5)));
        $this->assertEquals('1024 PB', FileHelper::humanFileSize(pow(1024, 6)));
    }

    public function testSizeFromIni()
    {
        $this->assertEquals(100, FileHelper::sizeFromIni('100'));
        $this->assertEquals(102400, FileHelper::sizeFromIni('100k'));
        $this->assertEquals(102400, FileHelper::sizeFromIni('100kb'));
        $this->assertEquals(104857600, FileHelper::sizeFromIni('100m'));
        $this->assertEquals(104857600, FileHelper::sizeFromIni('100mb'));
        $this->assertEquals(107374182400, FileHelper::sizeFromIni('100g'));
        $this->assertEquals(107374182400, FileHelper::sizeFromIni('100gb'));
        $this->assertEquals(1, FileHelper::sizeFromIni('1foo'));
        $this->assertEquals(0, FileHelper::sizeFromIni('foo'));
    }
}
