# Stan Pay Plugin in Sylius

Stan Pay is a French payment solution that allows e-commerce sites to offer their customers an ultra-fast checkout and an innovative alternative payment method to the bank card. Add the free Stan Pay plugin and make your Sylius store take off.

# Configuration

You will only need to create a free [Stan account](https://compte.stan-app.fr), and get your API credentials.

# Development

The payment gateway integration uses `stan-php` for the API Client. Learn more in the [documentation](https://doc.stan-app.fr).

## Contribution & Dev

### Running plugin tests

  - PHPUnit

    ```bash
    vendor/bin/phpunit
    ```

  - PHPSpec

    ```bash
    vendor/bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    vendor/bin/behat --strict --tags="~@javascript,@ui"
    ```

  - Behat (JS scenarios)
 
    1. [Install Symfony CLI command](https://symfony.com/download).
 
    2. Start Headless Chrome:
    
      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```
    
    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:
    
      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
      ```
    
    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript,@ui"
      ```
    
  - Static Analysis
  
    - Psalm
    
      ```bash
      vendor/bin/psalm
      ```
      
    - PHPStan
    
      ```bash
      vendor/bin/phpstan analyse -c phpstan.neon -l max src/  
      ```

  - Coding Standard
  
    ```bash
    vendor/bin/ecs check src
    ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    (cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=test symfony server:start --allow-http --dir public)
    ```
    
- Using `dev` environment:

    ```bash
    (cd tests/Application && APP_ENV=dev bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=dev symfony server:start --allow-http --dir public)
    ```

## OS x64 Sass fix

Update `vendor/sylius/sylius/src/Sylius/Bundle/{AdminBundle,ShopBundle}/gulpfile.babel.js` to add the following lines

`yarn add sass && yarn upgrade guld-sass`

```js
import realSass from 'sass';
import gulpSass from 'gulp-sass';

const sass = gulpSass(realSass);
```
