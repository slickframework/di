Feature: Container passed as argument when resolving the definition
  In order to access other entries
  As a developer
  I want to have the container passed as argument for defined closures

  Scenario: Use container inside closure
    Given I build a container with "closure-services.php"
    When I get "closureWithContainer" from container
    Then it should be an instance of "Fixtures\ComplexObject"