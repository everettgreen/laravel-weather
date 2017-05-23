# Laravel Queued Weather Tracking App #

Exercise:

- Create an application that will track current weather measurements for a given set of zip codes
- Store the following data at a minimum:
    - Zip code,
    - General weather conditions (e.g. sunny, rainy, etc),
    - Atmospheric pressure,
    - Temperature (in Fahrenheit),
    - Winds (direction and speed),
    - Humidity,
    - Timestamp (in UTC)
- There is no output requirement for this application, it is data retrieval and storage only
- The application should be able to recover from any errors encountered
- The application should be developed using a TDD approach. 100% code coverage is not required
- The set of zip codes and their respective retrieval frequency should be contained in configuration file
- Use the OpenWeatherMap API for data retrieval (https://openweathermap.org)

# Installation
- This repository depends on composer and was tested successfully with PHP7.1
- If you're running Mac or Linux, you should be able to run `./quickstart.sh`
    to perform the following steps automatically. Your mileage may vary on Windows.
    You'll still need to enter your own API key.
- After cloning this repository:
    - Copy `./.env.example` to `./.env` and update the OpenWeatherMap API key
    - Navigate to the project root and run `composer install`
    - Run `php artisan key:generate`
    - Create empty file `./database/database.sqlite`
    - Then, run `php artisan migrate` to install schema

# Notes
- I have used Laravel Framework 5.4 for this exercise.
- I have attempted to follow a TDD process for the first time, but only
    really added an integration test for the weather service.
- No rate-limit protection, although queue job could easily include `sleep(1)`
    to avoid exceeding 60 reqs/min max, assuming only 1 queue worker
- Cron command: `* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1`
    or, simulate by running from cli: `php artisan schedule:run`
- Queue worker command: `php artisan queue:work`
- If queue is not processing but cron continues to process, no logic
    is in place to flush outdated queued requests or their orphan snapshots.
    Design decision to include shell snapshots to make proper scheduling easier
    and avoid overlap, but upon reflection, both options have negatives
- Since weather data reflects a snapshot in time, delays in processing or collecting
    several snapshots for the same location that were backed up in the queue don't
    provide value.
- Further, there's no layer (caching or otherwise) to prevent pulling data 
    for a zip more frequently than it updates/is valuable.
- Exceptions are handled via queue. Stub for failure logic created,
    could be used to notify of failure, handle retry, delete orphan, etc.
- Postcode at the least should be an indexed column, since we're doing a lookup
    on it.

# Features
- Service to request weather data for zip
- Store weather data to persistent storage
- Configuration of zips and retrieval frequency managed by cron and queue
    for easy retry and failure tracking