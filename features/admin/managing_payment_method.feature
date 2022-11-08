@managing_payment_methods
Feature: Adding a new Stan Pay payment method
  In order to allow payment for orders, using the Stan Pay gateway
  As an Administrator
  I want to add new payment methods to the system

  Background:
    Given the store operates on a single channel in "United States"
    And I am logged in as an administrator

  @ui @javascript
  Scenario: Adding a new stan pay payment method
    Given I want to create a new Stan Pay payment method
    When I name it "Stan Pay" in "English (United States)"
    And I specify its code as "stan_pay"
    And I configure it with Stan Pay gateway data "TEST", "TEST"
    And I add it
    Then I should be notified that it has been successfully created
    And the payment method "Stan Pay" should appear in the registry