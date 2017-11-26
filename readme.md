## About Project

This is a demo application created as a test for the PHP Developer over at Rabbit Internet. It demonstrates the usage of google maps and twitter api. The framework used for this project is Laravel v5.4.36.

## Its functionalities

- a responsive website
- users can view a map
- users can search for different cities, which will then be loaded in the map
- it also uses the twitter api to load the tweets which mention the city entered by user and adds them to the map as markers
- it stores users search history
- it also caches the data received from twitter api

## Installation
```
$ git clone https://github.com/DezrtRose/rabbit-test.git {directory name}
$ cd {directory name}
$ composer install (after completion, rename the .env.example file in the root folder to .env)
$ php artisan serve (then head to http://localhost:8000 to view the project)
```

## Configuration
Add the following items to the .env file
- SEARCH_RADIUS=(search radius. eg: 50km)
- CACHE_TTL=(cache time to live. eg: 60)
- TWITTER_CONSUMER_KEY=(comsumer key of your twitter app)
- TWITTER_CONSUMER_SECRET=(comsumer secret key of your twitter app)
- TWITTER_ACCESS_TOKEN=(access token of your twitter app)
- TWITTER_ACCESS_TOKEN_SECRET=(secret access token of your twitter app)

## [Demo](http://feeds.condatbackup.com/public/)
