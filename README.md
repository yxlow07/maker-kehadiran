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
  - [x] ~~Email verification page (Cancelled)~~
  - [x] Announcement Page
- Admins
  - [x] CRUD Users
    - [x] Create
    - [x] Read
    - [x] Update
    - [x] Delete
  - [x] Upload attendance csv
  - [x] CRUD Attendance
    - [x] Create
    - [x] Read
    - [x] Update
    - [x] Delete
  - [x] Analysis Attendance
  - [x] CRUD Announcements
    - [x] Create
    - [x] Read
    - [x] Update
    - [x] Delete
- General
  - [x] Middlewares
  - [x] Styling

## Known Limitations
- [ ] Only supports one club [Multi activities supported by storing arrays/json objects in database]
- [x] Announcements are hard coded (Solved)
- [x] Website looks like _s_
- [ ] When updating password, all profile information disappeared
- [ ] Duplicate noTel might be registered
- [ ] $returns should not be used, instead straight throw exceptions and let main App catch them and render it
- [ ] (Admin attendance update) The number of activities for everyone is arbitrary. Just hope admin don't luan delete
- [ ] User list page, when too many users, will need pagination

## Solved Limitations
- [x] Announcements are coded to be read from csv file

## Simple features to be implemented (Backend)
- [x] Uploading to github
- [ ] Reusable form components

## Frequent Questions
1. Why isn't nikic/FastRoute chosen as the optimum routing solution?
    - It contains a lot of built-in functions that do not require much effort to use
    - It is [fast](https://www.npopov.com/2014/02/18/Fast-request-routing-using-regular-expressions.html)
    - But it is too **complicated**