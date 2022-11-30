# Stan Pay Installation

1. Run

    ```bash
    $ composer require stan-business/sylius-stan-pay-plugin
    ```
    
2. Add dependencies

    ```php
    // config/bundles.php
    return [
        ...
        Brightweb\SyliusStanPayPlugin::class => ['all' => true],
        ...
    ]
    ```

Next: [Onboarding](onboarding.md)