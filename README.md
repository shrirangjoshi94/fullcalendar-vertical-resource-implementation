This project is implementation of fullcalender.io library. Laravel is used in the backend and Jquery has been used on the client side.

To run the project after taking clone please run the below commands:

1)mv .env.example .env

2)Change the below 3 values in the .env file
DB_DATABASE=db_name
DB_USERNAME=bd_user_name
DB_PASSWORD=db_passowrd

3) composer install

4)composer dump-autoload

5) php artisan migrate

6) php artisan db:seed
