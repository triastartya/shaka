<!-- # Package for Laravel

## Basic Usage
Tambahkan repo `responisme` di `composer.json` :
```php
    "repositories": {
        "att/responisme": {
              "type": "vcs",
              "url": "https://gitlab.att.id/sofamh/responisme.git"             
        }
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

### Disable & Enable Redis 
Secara default method `callService` menggunakan `Redis` untuk cache. Jika ingin disable `Redis` bisa dengan cara :

```php
    use Att\Responisme\Traits\ConnectService;

    trait Example
    {
        use ConnectService;

        public function getStudentAttribute()
        {        
            return $this
                ->disableRedis() // Redis Off
                ->enableRedis()  // Redis On
                ->callService(URL, METHOD = 'GET', DATA as array);
        }
        
    }
```

### Request Timeout
Untuk mengatur `timeout` komunikasi antar service, bisa menggunakan method `timeout(seconds)`, default timeout adalah `1 Detik` :

```php
    use Att\Responisme\Traits\ConnectService;

    trait Example
    {
        use ConnectService;

        public function getStudentAttribute()
        {        
            return $this
                ->timeout(10) // timeout 10 detik
                ->callService(URL, METHOD = 'GET', DATA as array);
        }
        
    }
``` -->
