# Package for Laravel

## Basic Usage
Tambahkan repo `responisme` di `composer.json` :
```php
    "repositories": {
        "att/responisme": {
              "type": "vcs",
              "url": "https://gitlab.att.id/sofamh/responisme.git",              
        },
     }
```
Install `responisme` :

    > composer require att/responisme

## Contents

+ [Send Requests in Microservice](#request-microservice)
+ [Request Timeout in Microservice](#request-timeout)

### Request Microservice
Terdapat method `callService` yang bisa digunakan untuk request antar service, method `callService` ini menyimpan response ke `redis` maka jika terdapat request dg url yang sama, maka akan mengambil data dari `redis`. 

```php
    use Att\Responisme\Traits\ConnectService;

    trait Example
    {
        use ConnectService;

        public function getStudentAttribute()
        {        
            return $this->callService(URL, METHOD = 'GET', DATA as array);
        }
        
    }
```
