Feature: Store an alias definition
  In order to reuse an existing definition
  As a developer
  I wan to define an alias for an existing definition

  Scenario: use an alias definition
    Given I create a container
    And I define a callable that returns an object
    And register it under "object" key
    And register a "@object" under "alias" key
    When I get "alias" from container
    Then it should be the same as "object"