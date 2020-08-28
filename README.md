#### 1.) Prepare a mysql database

##### login to mysql with your user data
`mysql -uroot -proot`
##### create database
`CREATE DATABASE starwars;`
##### create user
`CREATE USER 'laravel'@'localhost' IDENTIFIED WITH mysql_native_password BY 'starwars';`
##### grant privileges to user
`GRANT ALL PRIVILEGES ON starwars.* TO 'laravel'@'localhost';`

#### 2.) git clone the repo
`git clone git@github.com:lzielonka/sw-api.git`

#### 3.) Create variables file
Create a `.env` file and copy contents of `.env.example` to it
Note: it assumes `starwars` as the database name and mysql user to be `laravel` with password `starwars`, adjust if needed.

#### 4.) Install dependencies
`composer install`

#### 5.) Execute migrations
`php artisan migrate`

#### 6.) Seed the database
`php artisan db:seed`

#### 7.) Generate app key
`php artisan key:generate`

#### 8.) Clear cache
`php artisan cache:clear`
`php artisan config:cache`

#### 8.) Launch the server (port 8080 is assumed in the swagger)
`php artisan serve --port=8080`

#### 9.) navigate to the api docs http://127.0.0.1:8080/api/documentation
