# PSON

**A powerful PHP library to convert objects into JSON and vice versa using PHP attributes**
## Features:
- Easy-to-use
- Extensible
- High performance
- Fully customization

## Getting started
## Attributes
PSON gets all object properties (including parent if exists) and if some properties has `JsonProperty` attribute, 
they will be converted into JSON. 
```php
<?php
class User{

    private int $id = 1; //won't be converted into JSON

    #[JsonProperty("name")]
    private string $name = "Daniel"; //will be converted into JSON like {"name": "Daniel"} 
}
```
## _Convert object into JSON:_
1. Create an `object` instance
2. Call `PSON::toJsonArray(object)` method
3. Call `json_encode` with your flags

**Example** - convert `User` object into JSON:

```php
<?php

declare(strict_types=1);

use luverelle\pson\attributes\JsonProperty;
use luverelle\pson\PSON;

class User{

    #[JsonProperty("name")]
    private string $name;
    
    #[JsonProperty("email")]
    private string $email;
    
    #[JsonProperty("rating")]
    private float $socialRating;

    public function __construct(string $name, string $email, float $socialRating){
        $this->name = $name;
        $this->email = $email;
        $this->socialRating = $socialRating;
    }
}

$user = new User("Jiang Xina", "china@gmail.cn", 999.99);
$jsonArray = PSON::toJsonArray($user);
echo json_encode($jsonArray, JSON_PRETTY_PRINT);
```
The result will be:
```json
{
    "name": "Jiang Xina",
    "email": "china@gmail.cn",
    "rating": 999.99
}
```

## _Convert JSON into object_:
1. Call `json_decode` with `associative: true`
2. If you have already instantiated your object, use `PSON::fromJsonArray`.
It returns nothing because PHP by default passing all objects by reference
3. Otherwise, use `PSON::fromJsonArrayAsClass` providing the class name of that object that you want to get (return).

**Example** - convert JSON into `User` (previous example but versa):

```php
<?php

declare(strict_types=1);

use luverelle\pson\attributes\JsonProperty;
use luverelle\pson\PSON;

class User{

    #[JsonProperty("name")]
    private string $name;
    
    #[JsonProperty("email")]
    private string $email;
    
    #[JsonProperty("rating")]
    private float $socialRating;

    public function __construct(string $name, string $email, float $socialRating){
        $this->name = $name;
        $this->email = $email;
        $this->socialRating = $socialRating;
    }
}

$json = '{
    "name": "Jiang Xina",
    "email": "china@gmail.cn",
    "rating": 999.99
}';
$user = PSON::fromJsonArrayAsClass(json_decode($json, true), User::class);
var_dump($user);
```
The result will be:
```php
object(User)#2 (3) {
  ["name":"User":private]=>
  string(10) "Jiang Xina"
  ["email":"User":private]=>
  string(14) "china@gmail.cn"
  ["socialRating":"User":private]=>
  float(999.99)
}
```

## Nested objects
If you have an object that contains another objects in properties, and you want to convert all together into JSON,
    just add `JsonProperty` attribute. Properties with that attribute will be converted into JSON.
It also works versa: JSON -> object. 
Example:
```php
<?php

declare(strict_types=1);

use luverelle\pson\attributes\JsonProperty;
use luverelle\pson\PSON;

class Address{

    #[JsonProperty("post_code")]
    private int $postCode;

    #[JsonProperty("city")]
    private string $city;

    public function __construct(int $postCode, string $city){
        $this->postCode = $postCode;
        $this->city = $city;
    }
}

class Contact{

    #[JsonProperty("name")]
    private string $name;

    #[JsonProperty("address")]
    private Address $address;

    public function __construct(string $name, Address $address){
        $this->name = $name;
        $this->address = $address;
    }
}

$contact = new Contact("Elizabeth", new Address(350000, "Krasnodar"));
$jsonArray = PSON::toJsonArray($contact);
echo json_encode($jsonArray, JSON_PRETTY_PRINT) . PHP_EOL;
var_dump(PSON::fromJsonArrayAsClass($jsonArray, Contact::class));
```
The result will be:
```php
{
    "name": "Elizabeth",
    "address": {
        "post_code": 350000,
        "city": "Krasnodar"
    }
}
object(Contact)#8 (2) {
  ["name":"Contact":private]=>
  string(9) "Elizabeth"
  ["address":"Contact":private]=>
  object(Address)#15 (2) {
    ["postCode":"Address":private]=>
    int(350000)
    ["city":"Address":private]=>
    string(9) "Krasnodar"
  }
}
```
## Arrays of objects
If you want to add a JSON convertable array of objects(like `User[]`), 
ensure to add what type of object you want to convert in `JsonProperty` attribute like this:
```php
<?php
class User{
    //some stuff
}
class UserManager{

    /**
     * @var User[]
     */
    #[JsonProperty("users", arrayValueClass: User::class)]
    private array $cachedUsers; //note that phpdoc isn't necessary for json, it's for type hinting in IDE
}
```

## TODO list:
- **Add** more **tests**
- Add **more customizable** stuff

## Requirements:
- **PHP 8.0** or higher
- **JSON PHP extension**