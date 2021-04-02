# Change Log

All notable changes to this project will be documented in this file. This project adheres
to [Semantic Versioning] (http://semver.org/). For change log format,
use [Keep a Changelog] (http://keepachangelog.com/).

## [1.1.4] - 2021-04-02

### Fixed

- Fixed ArrayHelper::traverseHas() not returning true for null value
- Fixed ArrayHelper::traverseGet() not returning a null value

## [1.1.3] - 2021-03-31

### Fixed

- Fixed ArrayHelper::traverseGet() not returning any default value on a non-existent final key
- Fixed ArrayHelper::traverseSet() not set value on a non-existent final key

## [1.1.2] - 2021-03-11

### Fixed

- ArrayHelper::traverseExists() returns true on non-existent final key

## [1.1.1] - 2020-11-05

### Added

- PHP 8 compatibility in `composer.json`

# Changed

- Bump PHP compatibility to 7.3

## Fixed

- StringHelper::removeAccents() returns empty string if error
- Bad image resize for portrait/landscape ratio
- ImageHelperTest::providerSizes() parameters name
- Deprecated assertRegExp() and assertNotRegExp() methods
- Cast value given to dechex() function in ImageHelper::gradientColor() method

## [1.1.0] - 2020-07-30

### Added

- Add support of GdImage class in PHP 8

### Changed

- Simplify FQN in sources

## [1.0.0] - 2020-02-17

First version