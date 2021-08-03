### Mini loan app

It is an app that allows authenticated users to go through a loan application.
User can apply to loan.
After the loan is approved, the user is able to submit the weekly loan repayments.

Admin can reject or approve the loan application.



I pushed  .env and .env.testing files.

Create 2 databse.
1) aspire_app
2) aspire_app_test

## Setup

```bash

# install composer
$ composer install

# run migration 
$ php artisan migrate

# run seeder 
$ php artisan db:seed

# install passort
$ php artisan passport:install

# test application
$ php artisan test

```

## Environment setting
``` bash
OAUTH_TOKEN_URL
APP_URL
```
replace the value of this variable with your project url in both environment file(.env and .env.testing)
