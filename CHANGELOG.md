# Changelog

All notable changes to this project will be documented in this file.

This file format based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

[Unreleased]

## [v2.7.0] - 2021-10-02
### Removed
- PHP supported versions < 7.4
### Fixed
- Deprecation when using PHP >= 8.0

## [v2.6.0] - 2020-03-05
### Adds
- `psr/container` PSR-11 dependency package
### Fixed
- sphinx-doc update
- sphinx-doc template search results link
### Removed
- Abandoned `container-interop/container-interop` dependency

## [v2.5.1] - 2019-03-19
### Fixed
- Behat fixture class named "Object" was breaking the tests
- Added sphinx-doc requirements for read the docs builds 

## [v2.5.0] - 2019-03-19
### Added
- Code of conduct
- Issue template
- Pull request template
### Changed
- Updated documentation library
### Removed
- Support for PHP <= 7.0

## [v2.4.0] - 2017-12-08
### Removed
- ``Slick/Common`` dependency
- ``@inject`` annotation
- ``ObjectHydratorInterface``s without annotations the hydrators are no longer needed
- phpunit was unnecessary as all tests are written for phpspec  

## [v2.3.1] - 2017-06-12
### Fixed
- Resolving dependencies with wrong container in a multi-container scenario was leading to missing
  dependency exception. Now Container resolves dependency properly.

## [v2.3.0] - 2017-06-06
### Added 
- Constructor auto-loader: Loads dependency based on constructor type hinted parameters 

## [v2.2.0] - 2017-01-24
### Added
- Container is passed as an argument in the callable definition when resolving  

## [v2.1.0] - 2017-01-06
### Added
- ``BackwardCompatibleDefinitionInterface`` to support old object definition methods.

### Fixed
- call()->with() consecutive call bug.
  Check [github issue #2](https://github.com/slickframework/di/issues/2) for details.
  
### Deprecates
- ``ObjectDefinition::setConstructArgs()``, ``ObjectDefinition::setMethod()`` and ``ObjectDefinition::setProperty()``.
  This methods will be removed in the next major release.

## [v2.0.2] - 2017-01-05
### Fixed
- Container builder does not override existing entries. This allows
  applications to set the main container and when other packages build
  their containers the first entries will be used instead.

## [v2.0.1] - 2017-01-03
### Fixed
- Now you can reference the container in a definitions file by using the
  ``@container`` tag

## [v2.0.0] - 2016-12-30
### Added
- A new interface for dependency injection
- Multiple definitions file load
- A more fluent object definition
- An interface for Slick\\Di\\ContainerInterface

### Removed
- Support for PHP 5.5
- Auto injecting dependencies on created objects
- Injecting unaccessible property
- ignoreInject annotation. No need for this as auto-injecting was removed

## [v1.0.2] - 2016-12-19
### Added
- New documentation theme

## [v1.0.1] - 2016-12-13
### Fixed
- Documentation bugs ans typos

## [v1.0.0] - 2016-12-12
### Added
- First release of Di package

[Unreleased]: https://github.com/slickframework/di/compare/v2.7.0...HEAD
[v2.7.0]: https://github.com/slickframework/di/compare/v2.6.0...v2.7.0
[v2.6.0]: https://github.com/slickframework/di/compare/v2.5.1...v2.6.0
[v2.5.1]: https://github.com/slickframework/di/compare/v2.5.0...v2.5.1
[v2.5.0]: https://github.com/slickframework/di/compare/v2.4.0...v2.5.0
[v2.4.0]: https://github.com/slickframework/di/compare/v2.3.1...v2.4.0
[v2.3.1]: https://github.com/slickframework/di/compare/v2.3.0...v2.3.1
[v2.3.0]: https://github.com/slickframework/di/compare/v2.2.0...v2.3.0
[v2.2.0]: https://github.com/slickframework/di/compare/v2.1.0...v2.2.0
[v2.1.0]: https://github.com/slickframework/di/compare/v2.0.2...v2.1.0
[v2.0.2]: https://github.com/slickframework/di/compare/v2.0.1...v2.0.2
[v2.0.1]: https://github.com/slickframework/di/compare/v2.0.0...v2.0.1
[v2.0.0]: https://github.com/slickframework/di/compare/v1.0.2...v2.0.0
[v1.0.2]: https://github.com/slickframework/di/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/slickframework/di/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/slickframework/di/compare/2c2205a...v1.0.0
