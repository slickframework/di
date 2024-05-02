Feature: Create service definitions file(s)
  In order to create a list of definitions for my application container
  As a developer
  I want to write php files with associative arrays that I use to build the container

  Scenario: Build a container with a single definitions file
    Given I build a container with "Service/main-services.php"
    When I try to get "complexObject" from container
    Then it should be an instance of "Fixtures\ComplexObject"

  Scenario: Built a container providing a directory of definition files
    Given I build a container with "Service"
    When I try to get "otherService" from container
    Then it should be an instance of "Fixtures\SomeObject"
