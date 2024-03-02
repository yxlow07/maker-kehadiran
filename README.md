## Features to be implemented
- [ ] Cron job scheduling: https://packagist.org/packages/dragonmantank/cron-expression
- [x] Faker PHP https://fakerphp.github.io/
- [ ] Guzzle HTTP requests https://docs.guzzlephp.org/en/stable/
- [ ] Symphony Mailer https://symfony.com/doc/current/mailer.html
- [ ] Fast Route https://github.com/nikic/FastRoute
- [ ] PHP Unit Testing https://www.freecodecamp.org/news/test-php-code-with-phpunit/
- [ ] Optimising Autoloading https://getcomposer.org/doc/articles/autoloader-optimization.md

## Pages for website
- Users
  - [x] Login / Register
  - [x] Profile Page
  - [x] Check Attendance Page
  - [x] Forgot Password Page
  - [x] Reset Password Page
  - [ ] Email verification page
  - [ ] Announcement Page
- Admins
  - [ ] CRUD Users
  - [ ] Upload attendance csv
  - [ ] CRUD Attendance
  - [ ] Analysis Attendance
- General
  - [ ] Styling

## Known Limitations
- [ ] Only supports one club [Multi activities supported by storing arrays/json objects in database]
- [ ] Announcements are hard 
- [ ] Website looks like _s_
- [ ] When updating password, all profile information disappeared

## Simple features to be implemented (Backend)
- [x] Uploading to github
- [ ] Reusable form components

## Frequent Questions
1. Why isn't nikic/FastRoute chosen as the optimum routing solution?
    - It contains a lot of built-in functions that do not require much effort to use
    - It is [fast](https://www.npopov.com/2014/02/18/Fast-request-routing-using-regular-expressions.html)
    - But it is too **complicated**
