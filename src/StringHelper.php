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
 * Class StringHelper.
 *
 * @package Berlioz\Helpers
 */
final class StringHelper
{
    // Random
    const RANDOM_NUMBER = 1;
    const RANDOM_SPECIAL_CHARACTERS = 2;
    const RANDOM_LOWER_CASE = 4;
    const RANDOM_NEED_ALL = 64;
    // Truncate
    const TRUNCATE_LEFT = 1;
    const TRUNCATE_MIDDLE = 2;
    const TRUNCATE_RIGHT = 3;

    /**
     * Generate an random string.
     *
     * @param int $length  Length of string
     * @param int $options Options
     *
     * @return string
     */
    public static function random(int $length = 12, int $options = self::RANDOM_NUMBER | self::RANDOM_SPECIAL_CHARACTERS | self::RANDOM_NEED_ALL): string
    {
        // Options
        $withNumber = ($options & self::RANDOM_NUMBER) == self::RANDOM_NUMBER;
        $withSpecialCharacter = ($options & self::RANDOM_SPECIAL_CHARACTERS) == self::RANDOM_SPECIAL_CHARACTERS;
        $onlyLowerCase = ($options & self::RANDOM_LOWER_CASE) == self::RANDOM_LOWER_CASE;
        $needAllRequiredParameters = ($options & self::RANDOM_NEED_ALL) == self::RANDOM_NEED_ALL;

        // Defaults
        $characters_lowercase = 'abcdefghkjmnopqrstuvwxyz';
        $characters_uppercase = 'ABCDEFGHKJMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialCharacters = '~!@#$%^&*()-_=+[]{};:,.<>/?';

        // Make global source
        $source = $characters_lowercase . ($onlyLowerCase === false ? $characters_uppercase : '') . ($withNumber === true ? $numbers : '') . ($withSpecialCharacter === true ? $specialCharacters : '');

        $length = abs(intval($length));
        $n = strlen($source);
        $str = [];

        // If all parameters are required
        if ($needAllRequiredParameters === true) {
            // Lower case
            $str[] = $characters_lowercase{mt_rand(1, strlen($characters_lowercase)) - 1};
            $length--;

            // Upper case
            if ($onlyLowerCase === false) {
                $str[] = $characters_uppercase{mt_rand(1, strlen($characters_uppercase)) - 1};
                $length--;
            }

            // Numbers
            if ($withNumber === true) {
                $str[] = $numbers{mt_rand(1, strlen($numbers)) - 1};
                $length--;
            }

            // Special characters
            if ($withSpecialCharacter === true) {
                $str[] = $specialCharacters{mt_rand(1, strlen($specialCharacters)) - 1};
                $length--;
            }
        }

        // Generate the main string
        for ($i = 0; $i < $length; $i++) {
            $str[] = $source{mt_rand(1, $n) - 1};
        }

        // Shuffle the string
        shuffle($str);

        return implode('', $str);
    }

    /**
     * Surrounds paragraphs with "P" HTML tag and inserts HTML line breaks before all newlines; in a string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function nl2p(string $str): string
    {
        $str = preg_split('/(\r?\n){2,}/', $str);
        array_walk(
            $str,
            function (&$str) {
                $str = '<p>' . nl2br(trim($str)) . '</p>';
            });

        return implode("\n", $str);
    }

    /**
     * Remove accents.
     *
     * @param string $str
     *
     * @return string
     */
    public static function removeAccents(string $str): string
    {
        $str = transliterator_transliterate('Any-Latin; Latin-ASCII', $str);

        return $str;
    }

    /**
     * String to URI string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function strToUri(string $str): string
    {
        $str = self::removeAccents($str);
        $str = strtolower($str);
        $str = preg_replace('/[^0-9a-z\-]+/', '-', $str);
        $str = preg_replace('/-{2,}/', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

    /**
     * Minify HTML string.
     *
     * @param string $str
     *
     * @return string
     * @link https://stackoverflow.com/a/5324014
     */
    public static function minifyHtml(string $str): string
    {
        // Save and change PHP configuration value
        $oldPcreRecursionLimit = ini_get('pcre.recursion_limit');
        ini_set('pcre.recursion_limit', PHP_OS == 'WIN' ? '524' : '16777');

        $regex = <<<EOT
%# Collapse whitespace everywhere but in blacklisted elements.
(?>             # Match all whitespans other than single space.
  [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
| \s{1,}        # or two or more consecutive-any-whitespace.
) # Note: The remaining regex consumes no text at all...
(?=             # Ensure we are not in a blacklist tag.
  [^<]*+        # Either zero or more non-"<" {normal*}
  (?:           # Begin {(special normal*)*} construct
    <           # or a < starting a non-blacklist tag.
    (?!/?(?:textarea|pre|script)\b)
    [^<]*+      # more non-"<" {normal*}
  )*+           # Finish "unrolling-the-loop"
  (?:           # Begin alternation group.
    <           # Either a blacklist start tag.
    (?>textarea|pre|script)\b
  | \z          # or end of file.
  )             # End alternation group.
)  # If we made it here, we are not in a blacklist tag.
%Six
EOT;

        // Reset PHP configuration value
        ini_set('pcre.recursion_limit', $oldPcreRecursionLimit);

        $str = preg_replace($regex, ' ', $str);

        return $str;
    }

    /**
     * Truncate string.
     *
     * @param string $str          String
     * @param int    $nbCharacters Number of characters
     * @param int    $where        Where option: B_TRUNCATE_LEFT, B_TRUNCATE_MIDDLE or B_TRUNCATE_RIGHT
     * @param string $separator    Separator string
     *
     * @return string
     */
    public static function truncate(string $str, int $nbCharacters = 128, int $where = self::TRUNCATE_RIGHT, string $separator = '...'): string
    {
        $str = html_entity_decode($str);

        if (mb_strlen(trim($str)) > 0 && mb_strlen(trim($str)) > $nbCharacters) {
            switch ($where) {
                case self::TRUNCATE_LEFT:
                    $str = $separator . ' ' . mb_substr($str, intval(mb_strlen($str) - $nbCharacters, mb_strlen($str)));
                    break;
                case self::TRUNCATE_RIGHT:
                    $str = mb_substr($str, 0, $nbCharacters) . ' ' . $separator;
                    break;
                case self::TRUNCATE_MIDDLE:
                    $str = mb_substr($str, 0, intval(ceil($nbCharacters / 2))) .
                           ' ' .
                           $separator .
                           ' ' .
                           mb_substr($str, intval(mb_strlen($str) - floor($nbCharacters / 2)), mb_strlen($str));
                    break;
            }
        }

        return $str;
    }

    /**
     * Get pascal case convention of string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function pascalCase(string $str): string
    {
        $str =
            preg_replace_callback(
                '/(?:^|_)(.?)/',
                function ($matches) {
                    return mb_strtoupper($matches[1]);
                },
                $str
            );

        return $str;
    }

    /**
     * Get camel case convention of string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function camelCase(string $str): string
    {
        $str = self::pascalCase($str);
        $str = mb_strtolower(substr($str, 0, 1)) . substr($str, 1);

        return $str;
    }

    /**
     * Get snake case convention of string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function snakeCase(string $str): string
    {
        $str =
            preg_replace_callback(
                '/([a-z0-9])([A-Z])/',
                function ($matches) {
                    return sprintf('%s_%s', $matches[1], mb_strtolower($matches[2]));
                },
                $str
            );
        $str = mb_strtolower($str);

        return $str;
    }

    /**
     * Get spinal case convention of string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function spinalCase(string $str): string
    {
        $str =
            preg_replace_callback(
                '/([a-z0-9])([A-Z])/',
                function ($matches) {
                    return sprintf('%s-%s', $matches[1], mb_strtolower($matches[2]));
                },
                $str
            );
        $str = str_replace('_', '-', $str);
        $str = mb_strtolower($str);

        return $str;
    }
}