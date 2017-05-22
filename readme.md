# minPHP Weather App #

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

# Notes
- I have used Laravel Framework 5.4 for this exercise.
- I have attempted to follow a TDD process for the first time

# Features
- Service to request weather data for zip
- Store weather data to persistent storage
- Configuration of zips and retrieval frequency managed by cron