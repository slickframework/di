Feature: Scope aware resolution
  In order to get same or new instance of a definition
  As a developer
  I want to specify the scope of a definition

  Notes:
    - singleton: The instance of a definition is reused
    - prototype: A new instance is creates every

  Scenario: Get a service as singleton scope
    Given I create a container
    And I define a callable that returns an object
    And register it under "singleton-scope" key with "singleton" scope
    When I get "singleton-scope" from container
    Then it should be the same as "singleton-scope"

  Scenario: Get a service as prototype scope
    Given I create a container
    And I define a callable that returns an object
    And register it under "prototype-scope" key with "prototype" scope
    When I get "prototype-scope" from container
    Then it should be the equal to "prototype-scope"
    And it should not be the same as "prototype-scope"