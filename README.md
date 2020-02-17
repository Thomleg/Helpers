# Berlioz Helpers (PHP Functions)

Many PHP functions used in the Berlioz framework, which you can use in your developments.

## Array

  - `b_array_is_sequential(array $array): bool`

     Is sequential array?

  - `b_array_merge_recursive(array $arraySrc, array ...$arrays): array`

    Merge two or more arrays recursively.

    Difference between native array_merge_recursive() is that
    b_array_merge_recursive() do not merge strings values
    into an array.

  - `b_array_traverse_exists(&$mixed, string $path): bool`

    Traverse array with path and return if path exists.

  - `b_array_traverse_get(&$mixed, string $path, $default = null): mixed|null`

    Traverse array with path and get value.

  - `b_array_traverse_set(&$mixed, string $path, $value): bool`

    Traverse array with path and set value.

## File

  - `b_human_file_size($size, int $precision = 2): string`

    Get a human see file size.

  - `b_size_from_ini(string $size): int`

    Get size in bytes from ini conf file.

## File

  - `b_get_property_value($object, string $property, &$exists = null): mixed`

    Get property value with getter method.

  - `b_set_property_value($object, string $property, $value): bool`

    Set property value with setter method.

## String

  - `b_str_random(int $length = 12, int $options = B_STR_RANDOM_NUMBER | B_STR_RANDOM_SPECIAL_CHARACTERS | B_STR_RANDOM_NEED_ALL): string`

    Generate an random string.

  - `b_nl2p(string $str): string`

    Surrounds paragraphs with "P" HTML tag and inserts HTML line breaks before all newlines; in a string.

  - `b_str_remove_accents(string $str): string`

    Remove accents.

  - `b_str_to_uri(string $str): string`

    String to URI string.

  - `b_minify_html(string $str): string`

    Minify HTML string.

  - `b_str_truncate(string $str, int $nbCharacters = 128, int $where = B_TRUNCATE_RIGHT, string $separator = '...'): string`

    Truncate string.

  - `b_pascal_case(string $str): string`

    Get pascal case convention of string.

  - `b_camel_case(string $str): string`

    Get camel case convention of string.

  - `b_snake_case(string $str): string`

    Get snake case convention of string.

  - `b_spinal_case(string $str): string`

    Get spinal case convention of string.