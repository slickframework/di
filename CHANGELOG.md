# Changelog

All Notable changes to `Slick/di` will be documented in this file

## 2.4.0 - 2017-12-08

### Removed
- ``Slick/Common`` dependency
- ``@inject`` annotation
- ``ObjectHydratorInterface``s without annotations the hydrators are no longer needed
- phpunit was unnecessary as all tests are written for phpspec  

## 2.3.1 - 2017-06-12

### Fixed
- Resolving dependencies with wrong container in a multi-container scenario was leading to missing
  dependency exception. Now Container resolves dependency properly.

## 2.3.0 - 2017-06-06

### Added 
- Constructor auto-loader: Loads dependency based on constructor type hinted parameters 

## 2.2.0 - 2017-01-24

### Added
- Container is passed as an argument in the callable definition when resolving  

## 2.1.0 - 2017-01-06

### Added
- ``BackwardCompatibleDefinitionInterface`` to support old object definition methods.

### Fixed
- call()->with() consecutive call bug.
  Check [github issue #2](https://github.com/slickframework/di/issues/2) for details.
  
### Deprecates
- ``ObjectDefinition::setConstructArgs()``, ``ObjectDefinition::setMethod()`` and ``ObjectDefinition::setProperty()``.
  This methods will be removed in the next major release.

## 2.0.2 - 2017-01-05

### Fixed
- Container builder does not override existing entries. This allows
  applications to set the main container and when other packages build
  their containers the first entries will be used instead.

## 2.0.1 - 2017-01-03

### Fixed
- Now you can reference the container in a definitions file by using the
  ``@container`` tag

## 2.0.0 - 2016-12-30

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

## 1.0.2 - 2016-12-19

### Added
- New documentation theme

## 1.0.1 - 2016-12-13

### Fixed
- Documentation bugs ans typos

## 1.0.0 - 2016-12-12

### Added
- First release of Di package

