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

    public function absolutePathProvider(): array
    {
        return [
            ['index.md', 'foo/bar/foo.md', '/foo/bar/foo.md'],
            ['/index.md', '/foo/bar/baz.md', '/foo/bar/baz.md'],
            ['/index.md', 'bar.md', '/bar.md'],
            ['/index.md', '/baz.md', '/baz.md'],
            ['foo/bar/index.md', 'baz/qux.md', '/foo/bar/baz/qux.md'],
            ['foo/bar/index.md', '/baz/qux.md', '/baz/qux.md'],
            ['foo/bar/index.md', './baz.md', '/foo/bar/baz.md'],
            ['foo/bar/index.md', '../baz.md', '/foo/baz.md'],
            ['foo/bar/index.md', '../../qux.md', '/qux.md'],
            ['foo/bar/index', '../../qux/quux.foo', '/qux/quux.foo'],
            ['foo/bar/index.md', '../../../qux.md', null],
            ['foo/bar/index', '../../qux/quux.foo#anchor', '/qux/quux.foo#anchor'],
        ];
    }

    /**
     * @param $src
     * @param $dst
     * @param $excepted
     *
     * @dataProvider absolutePathProvider
     */
    public function testResolveAbsolutePath($src, $dst, $excepted)
    {
        $this->assertEquals($excepted, FileHelper::resolveAbsolutePath($src, $dst));
    }

    public function relativePathProvider(): array
    {
        return [
            ['index.md', 'foo/bar/foo.md', './foo/bar/foo.md'],
            ['index.md', '/foo/bar/baz.md', './foo/bar/baz.md'],
            ['/index.md', 'foo/bar/bar.md', './foo/bar/bar.md'],
            ['/index.md', 'bar.md', './bar.md'],
            ['/index.md', '/baz.md', './baz.md'],
            ['./index.md', '/qux.md', './qux.md'],
            ['index.md', 'quux.md', './quux.md'],
            ['foo/index.md', '/foo/baz.md', './baz.md'],
            ['./foo/bar/index.md', '/baz.md', '../../baz.md'],
            ['/foo/bar/index.md', '/qux/baz.md', '../../qux/baz.md'],
            ['/foo/bar/index.md', 'qux/baz.md', './qux/baz.md'],
            ['/foo/bar/quux/index.md', '/foo/qux/corge/baz.md', '../../qux/corge/baz.md'],
            ['./foo/index.md', './bar/baz.md', './bar/baz.md'],
            ['foo/index.md', '/bar/baz.md', '../bar/baz.md'],
            ['./foo/index.md', '../bar/baz.md', '../bar/baz.md'],
            ['./foo/index.md', '../foo/baz.md', './baz.md'],
            ['./foo/index.md', '../foo/baz.md#anchor', './baz.md#anchor'],
        ];
    }

    /**
     * @dataProvider relativePathProvider
     */
    public function testResolveRelativePath($src, $dst, $excepted)
    {
        $this->assertEquals($excepted, FileHelper::resolveRelativePath($src, $dst));
    }
}
