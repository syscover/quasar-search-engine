# Quasar Search Engine

[![Total Downloads](https://poser.pugx.org/syscover/quasar-search-engine/downloads)](https://packagist.org/packages/syscover/quasar-search-engine)

## Installation

**1 - After install Laravel framework, execute on console:**
```
composer require quasar/quasar-search-engine
```

**2 - Execute publish command**
```
php artisan vendor:publish --provider="Quasar\SearchEngine\SearchEngineServiceProvider"
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

**3 - To config pulsar search scout driver, set scout configuration in your .env file wit this parameter**
```
SCOUT_DRIVER=quasar-search
```
