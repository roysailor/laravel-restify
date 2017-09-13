Laravel Restify
=========================

Automate Restful GET endpoints.

## Install

```
composer require roysailor/laravel-restify
```

Register the provider by adding the below lines to config/app.php under provider section.

```
 RoySailor\Restify\RestifyServiceProvider::class
```

## Generate Rest Model

Run the below command to generate the Rest Model

```
php artisan restify:generate_model
```

## Configuring Rest Model

To specify the table

```php
protected $tableName = 'cars';
```

To specify the filterable fields

```php
protected $filterables = [
      'status',
      'car_class'
    ];
```

To specify the sortable fields

```php
protected $sortables = [
      'price',
      'make'
    ];
```

To specify searchable fields

```php
protected $searchables = [
       'make',
       'model'
    ];
```

To specify Joins

```php
protected $joins = [
        'body_style' => 'body_styles.style_id'
    ];
```

```php
protected $joins = [
        'city.state_id' => 'state.state_id'
    ];
```

To specify fields

```php
protected $fields = [
        'id',
        'make',
        'model',
        'version',
        'status',
        'car_class',
        'price'
    ];
```

## Sample Controller

```php
namespace App\Http\Controllers;

use App\RestModels\CarRestModel;

class CarsController extends Controller
{

    public function index(CarRestModel $carModel){

        return $carModel->fetchJSON();

    }

}
```

## Recognized URL Pattern

### Limit fields
```
http://cars.com/cars?fields=id,make,model,version,status,car_class,price
```

### Filter
```
http://cars.com/cars?make=Chevrolet,status=Production
```

### Sort
```
http://cars.com/cars?sort=price,-make
```

### Search
```
http://cars.com/cars?q=Chev
```

### Paginate
```
http://cars.com/cars?page=2&per_page=20
```

### Embed
```
http://cars.com/cars?embed=reviews
```
Embed is under development right now.