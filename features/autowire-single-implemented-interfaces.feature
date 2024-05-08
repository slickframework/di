Feature: autowire single implemented interface
  In order to minimize the work on service definitions
  As a developer
  I want to autowire/autoconfigure single interface implementations

  Scenario: create dependency without arguments
    Given I build a container with autowire loader on "Fixtures/Interfaces"
    When I try to get "Fixtures\Interfaces\InterfaceDependentObject" from container
    Then it should be an instance of "Fixtures\Interfaces\InterfaceDependentObject"