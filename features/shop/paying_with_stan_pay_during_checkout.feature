@paying_with_stan_pay_during_checkout
Feature: Paying with Stan Pay during checkout
  In order to buy products
  As a Customer
  I want to be able to pay with Stan Pay

  Background:
    Given the store operates on a single channel in "United States"
    And there is a user "john@example.com" identified by "st4nner"
    And the store has a Stan Pay payment method "Stan Pay" with a code "stan_pay"
    And the store has a product "PHP T-Shirt" priced at "€19.99"
    And the store ships everywhere for free
    And I am logged in as "john@example.com"

  @ui
  Scenario: Successful payment in Stan Pay
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Stan Pay" payment method
    When I confirm my order with Stan Pay payment
    And I get redirected to Stan Pay and complete my payment
    Then I should be notified that my payment has been completed
    And I should see the thank you page
    And the latest order should have a payment with state "completed"