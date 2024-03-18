# Speedcube Timer
### Timer web application for speedcubers created with Laravel 10 and Vue 3
  

**Run project locally**

Requirements:
- [Composer](https://getcomposer.org/download/)
- [Git](https://git-scm.com/downloads)
- [PHP](https://www.php.net/downloads.php) (version 8.1 or newer)
- [Node.js](https://nodejs.org/en)

Installation:
1. Rename .env.example file to .env
1. Fill .env file with your database information
2. Open console and cd to project root directory (speedcube-timer-main)
3. Run following commands:

 - Install required dependencies
    > composer install

 - Generate application key
    > php artisan key:generate

 - Run database migrations
    > php artisan migrate

- Seed the database with dunmmy data, it also creates a test account with verified email (optional)
    > php artisan db:seed

5. Open another console and cd to vue directory (speedcube-timer-main/vue)
 
 - Install node dependencies
    > npm install

6. Run the project
   - in the main directory (speedcube-timer-main)
     > php artisan serve
   - in the vue directory (speedcube-timer-main/vue)
     > npm run dev
7. View the project at localhost:5173

   You can log in using the test account created while database seeding:
   ```
    Nickname: TestUser
    Password: test123
   ```
