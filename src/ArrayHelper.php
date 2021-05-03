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

use ArrayObject;
use InvalidArgumentException;
use SimpleXMLElement;
use Traversable;

/**
 * Class ArrayHelper.
 *
 * @package Berlioz\Helpers
 */
final class ArrayHelper
{
    /**
     * Is sequential array?
     *
     * @param array $array
     *
     * @return bool
     */
    public static function isSequential(array $array): bool
    {
        if ($array === []) {
            return true;
        }

        if (!array_key_exists(0, $array)) {
            return false;
        }

        $keys = array_keys($array);
        sort($keys);

        return $keys === range(0, count($array) - 1);
    }

    /**
     * Convert array to an XML element.
     *
     * @param $array
     * @param SimpleXMLElement|null $root
     * @param string|null $rootName
     *
     * @return SimpleXMLElement
     */
    public static function toXml($array, ?SimpleXMLElement $root = null, ?string $rootName = null): SimpleXMLElement
    {
        // Traversable or array
        if (!(is_array($array) || $array instanceof Traversable)) {
            throw new InvalidArgumentException('First argument must be an array or instance of \Traversable interface');
        }

        if (null === $root) {
            $root = new SimpleXMLElement(sprintf('<root/>'));
        }

        foreach ($array as $key => $value) {
            if (is_array($value) || $value instanceof Traversable) {
                if (static::isSequential($value)) {
                    static::toXml($value, $root, (string)$key);
                    continue;
                }

                static::toXml($value, $root->addChild((string)($rootName ?? $key)));
                continue;
            }

            $root->addChild((string)($rootName ?? $key), $value);
        }

        return $root;
    }

    /**
     * Merge two or more arrays recursively.
     *
     * Difference between native array_merge_recursive() is that
     * b_array_merge_recursive() do not merge strings values
     * into an array.
     *
     * @param array[] $arrays Arrays to merge
     *
     * @return array
     */
    public static function mergeRecursive(array ...$arrays): array
    {
        $arraySrc = array_shift($arrays);

        if (null === $arraySrc) {
            return [];
        }

        foreach ($arrays as $array) {
            if (empty($array)) {
                continue;
            }

            if (empty($arraySrc)) {
                $arraySrc = $array;
                continue;
            }

            if (self::isSequential($arraySrc) || self::isSequential($array)) {
                $arraySrc = array_merge($arraySrc, $array);
                continue;
            }

            foreach ($array as $key => $value) {
                if (!array_key_exists($key, $arraySrc)) {
                    $arraySrc[$key] = $value;
                    continue;
                }

                if (is_array($arraySrc[$key]) && is_array($value)) {
                    $arraySrc[$key] = self::mergeRecursive($arraySrc[$key], $value);
                    continue;
                }

                $arraySrc[$key] = $value;
            }
        }

        return $arraySrc;
    }

    /**
     * Traverse array with path and return if path exists.
     *
     * @param iterable $mixed Source
     * @param string $path Path
     *
     * @return bool
     */
    public static function traverseExists(iterable &$mixed, string $path): bool
    {
        $path = explode('.', $path);

        $temp = &$mixed;
        foreach ($path as $key) {
            if (!is_iterable($temp)) {
                return false;
            }

            // An array, so we check existent of key
            if (is_array($temp) && !array_key_exists($key, $temp)) {
                return false;
            }

            // Not an array, so isset
            if (!is_array($temp) && !isset($key, $temp)) {
                return false;
            }

            $temp = &$temp[$key];
        }

        return true;
    }

    /**
     * Traverse array with path and get value.
     *
     * @param iterable $mixed Source
     * @param string $path Path
     * @param mixed $default Default value
     *
     * @return mixed|null
     */
    public static function traverseGet(iterable &$mixed, string $path, $default = null)
    {
        $path = explode('.', $path);

        $temp = &$mixed;
        foreach ($path as $key) {
            if (!is_iterable($temp)) {
                return $default;
            }

            // An array, so we check existent of key
            if ((is_array($temp) || $temp instanceof ArrayObject) && !array_key_exists($key, $temp)) {
                return $default;
            }

            // Not an array, so isset
            if (!(is_array($temp) || $temp instanceof ArrayObject) && !isset($key, $temp)) {
                return $default;
            }

            $temp = &$temp[$key];
        }

        return $temp;
    }

    /**
     * Traverse array with path and set value.
     *
     * @param iterable $mixed Source
     * @param string $path Path
     * @param mixed $value Value
     *
     * @return bool
     */
    public static function traverseSet(iterable &$mixed, string $path, $value): bool
    {
        $path = explode('.', $path);

        $temp = &$mixed;
        foreach ($path as $key) {
            if (null !== $temp && !is_iterable($temp)) {
                return false;
            }

            if (!isset($temp[$key])) {
                $temp[$key] = null;
            }

            $temp = &$temp[$key];
        }
        $temp = $value;

        return true;
    }
}