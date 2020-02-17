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

declare(strict_types=1);

namespace Berlioz\Helpers;

/**
 * Class FileHelper.
 *
 * @package Berlioz\Helpers
 */
final class FileHelper
{
    /**
     * Get a human see file size.
     *
     * @param int|float $size
     * @param int $precision
     *
     * @return string
     */
    public static function humanFileSize($size, int $precision = 2): string
    {
        if (!is_numeric($size)) {
            return (string)$size;
        }

        // PB
        if (($size / pow(1024, 5)) >= 1) {
            return sprintf(
                "%s PB",
                round($size / pow(1024, 5), $precision)
            );
        }

        // TB
        if (($size / pow(1024, 4)) >= 1) {
            return sprintf(
                "%s TB",
                round($size / pow(1024, 4), $precision)
            );
        }

        // GB
        if (($size / pow(1024, 3)) >= 1) {
            return sprintf(
                "%s GB",
                round($size / pow(1024, 3), $precision)
            );
        }

        // MB
        if (($size / pow(1024, 2)) >= 1) {
            return sprintf(
                "%s MB",
                round($size / pow(1024, 2), $precision)
            );
        }

        // KB
        if (($size / pow(1024, 1)) >= 1) {
            return sprintf(
                "%s KB",
                round($size / pow(1024, 1), $precision)
            );
        }

        // Bytes
        return sprintf("%s bytes", $size);
    }

    /**
     * Get size in bytes from ini conf file.
     *
     * @param string $size
     *
     * @return int
     */
    public static function sizeFromIni(string $size): int
    {
        switch (mb_strtolower(substr($size, -1))) {
            case 'k':
                return (int)substr($size, 0, -1) * 1024;
            case 'm':
                return (int)substr($size, 0, -1) * 1024 * 1024;
            case 'g':
                return (int)substr($size, 0, -1) * 1024 * 1024 * 1024;
            default:
                switch (mb_strtolower(substr($size, -2))) {
                    case 'kb':
                        return (int)substr($size, 0, -2) * 1024;
                    case 'mb':
                        return (int)substr($size, 0, -2) * 1024 * 1024;
                    case 'gb':
                        return (int)substr($size, 0, -2) * 1024 * 1024 * 1024;
                    default:
                        return (int)$size;
                }
        }
    }
}