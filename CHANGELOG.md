# Changelog

All Notable changes to `Slick/di` will be documented in this file

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

