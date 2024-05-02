Feature: Try to create all necessary dependencies
  In order to remove the need to define all dependencies
  As a developer
  I want the container to make all necessary dependencies if/when no defined

  Scenario: Create undefined object
    Given I build a container with "Service/main-services.php"
    When I try to get "Fixtures\ObjectWithDependencies" from container
    Then it should be an instance of "Fixtures\ObjectWithDependencies"

  Scenario: Create undefined object with a dependency that cannot be initialized
    Given I build a container with "Service/main-services.php"
    When I get "Fixtures\ObjectWithInterfaceDependency" from container
    Then exception "Slick\Di\Exception\NotFoundException" should be thrown