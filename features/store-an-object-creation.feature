Feature: Store a definition of object creation
  I order to define an object creation (definition)
  As a developer
  I want to specify the the following:
    - class name
    - constructor arguments
    - method calls with/without arguments
    - property assignments

  Note:
    - In order to set parameters on constructors, methods and properties it
      must be possible to refer to entries on a container passed to the
      definition by using an '@' prefixing the entry name.

  Scenario: Create a simple object
    Given I create an object definition for class "Fixtures\SomeObject"
    And I create a container
    And register it under "simple-object" key
    When I get "simple-object" from container
    Then it should be an instance of "Fixtures\SomeObject"

  Scenario: Create object with constructor arguments
    Given I create a container
    And I create an object definition for class "Fixtures\ComplexObject" with:
      | @test | 1 |
    And register it under "other-object" key
    And register a "a test" under "test" key
    When I get "other-object" from container
    Then it should be an instance of "Fixtures\ComplexObject"

  Scenario: Create object with method calls and property assignments
    Given I create a container
    And I create an object definition for class "Fixtures\ComplexObject" with:
      | @test1 | 2 |
    And I add a call to method "setTest" with "test"
    And I assign "@test1" to property "other"
    And register it under "object-method-call" key
    And register a "other test" under "test1" key
    When I get "object-method-call" from container
    Then it should be an instance of "Fixtures\ComplexObject"
    And it should have a property "alias" equals to "other test"
    And it should have a property "other" equals to "other test"
