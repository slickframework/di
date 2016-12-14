Feature: Use the container as a factory
  In order to create objects that need dependencies
  As a developer
  I want to use the dependency container as a factory

  Notes:
    - Create object providing the class name and optional arguments
    - Arguments prefixed with '@' are seen as container entries
    - Object that implement a container injection interface can use the
      container to build themselves

  Scenario: Create an object without dependencies
    Given I create a container
    When I use the container to create "Fixtures\Object"
    Then it should be an instance of "Fixtures\Object"

  Scenario: Create an object with argument alias
    Given I create a container
    And register a "123" under "some-value" key
    When I use the container to create "Fixtures\ComplexObject" width:
      | @some-value |
    Then it should be an instance of "Fixtures\ComplexObject"

  Scenario: Create a container injection interface implementation
    Given I create a container
    And register a "43" under "other-value" key
    When I use the container to create "Fixtures\OtherObject"
    Then it should be an instance of "Fixtures\OtherObject"
