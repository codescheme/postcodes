**not yet loaded to packagist...**

# Postcodes

A Laravel 5+ facade/service provider for the GET methods of the API at ```postcodes.io``` - useful for UK postcode validation and reverse geocoding: that is, determining postcode from lat, long coordinates.
**No fiddling around api keys, authentication necessary...**

With thanks to https://postcodes.io


## Installation

Add `postcodes` to `composer.json`.
```
"codescheme/postcodes": "~1.2"
```

And run 
```
composer update
``` 

Or, directly, run
```
composer require codescheme/postcodes
```

Now open `/config/app.php` and add the service provider to your `providers` array.
```php
'providers' => [
	Codescheme\Postcodes\PostcodeServiceProvider::class,
]
```

Also add the alias:
```php
'aliases' => [
	'Postcode' => Codescheme\Postcodes\Facades\Postcode::class,
]
```

## Example Usage

Return data on a given postcode

```php
Route::get('/postcode', function(){
		 return Postcode::postcodeLookup('SE21 8JL', 'json');
});	
```

```
postcode @param string :
format @param string : 'object|json|array' (default:object)
```

## Methods

```
Postcode::validate('SE31 9AZ') //returns boolean
Postcode::postcodeLookup('SE21 8JL', 'array')
Postcode::nearest('SE21 8JL', 'json');
Postcode::reverseGeocode(-0.397913, 51.44015, 'json')
Postcode::autocomplete('SE21 8', 'json')
Postcode::outcodeLookup('SE21', 'object')
```

