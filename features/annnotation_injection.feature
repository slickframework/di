Feature: Annotation injection
  In order to inject dependencies in an object created by the container
  As a developer
  I want ti use a @inject annotation where I can set the entry names to use

  Notes:
    - The @inject annotation is used in public methods of the class;
    - It can define an alias or a list of alias
    - Alias MUST be a most the same as the method arguments
    - It should be set after creation by container or resolution
    - An unknown dependency rain a 'NotFoundException'

  Scenario: Inject a single entry
    Given I create a container
    And register a "test" under "singleInject" key
    When I use the container to create "Fixtures\SingleInject"
    Then it should have a property "value" equals to "test"