Feature: In order to specify a setter that should be called when object is constructed
  As a developer
  I want to add an @Autowired attribute to a setter

  User can specify the service, if not, the argument type wil. be used.

  Scenario: Create an object without autowire attribute
    Given I create a container
    When I use the container to create "Fixtures\ObjectWithGetter"
    Then it should be an instance of "Fixtures\ObjectWithGetter"
    And calling "foo" method should not return null
    And calling "environment" method should return "development"
    And calling "test" method should return "foo"