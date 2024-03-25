### Timer web application for speedcubers created with Laravel 10 and Vue 3
## Getting started

**Requirements**
- [Composer](https://getcomposer.org/download/)
- [PHP](https://www.php.net/downloads.php) (version 8.1 or newer)
- [Node.js](https://nodejs.org/en)

**Install locally**
1. Clone or download the repository

   `git clone https://github.com/Roszakos/speedcube-timer.git`
2. Rename .env.example file to .env
3. Fill .env file with your database information
4. Open console and navigate to project root directory (`speedcube-timer`)
5. Run following commands:

 - Install required dependencies
   
    `composer install`

 - Generate application key
   
    `php artisan key:generate`

 - Run database migrations
   
    `php artisan migrate`

- Seed the database with dummy data, it also creates a test account with verified email (optional)
  
    `php artisan db:seed`

6. Open another console and cd to vue directory (`speedcube-timer/vue`)
 
 - Install node dependencies
   
    `npm install`

7. Run the project
   - in the main directory (speedcube-timer-main)
     
     `php artisan serve`
   - in the vue directory (speedcube-timer-main/vue)
     
     `npm run dev`
8. View the project at localhost:5173

   You can log in using the test account created during database seeding:
   ```
    Nickname: TestUser
    Password: test123
   ```
