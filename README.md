# About the project
API Server for yourhometonight, a platform for hotel booking!

### Built with
- [PHP 8.1](https://www.php.net/releases/8_1_0.php)
- [MySQL 8](https://dev.mysql.com/doc/relnotes/mysql/8.0/en/)
- [Symfony 6.1](https://symfony.com/doc/6.1/index.html)

## Installation
Install Nginx following this [article](https://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-20-04).

Create account on [AWS](https://aws.amazon.com/) to use the S3 service.

#### Clone project 
```bash
$ git clone https://github.com/KN3T/yourHomeTonightBE.git
```
#### Install Composer
```bash
$ composer install
```

## Usage

#### Install AWS SDK for PHP
```bash
$ composer require aws/aws-sdk-php
```

#### Install PHPUnit
```bash
$ composer require --dev phpunit/phpunit
```
#### Convention check
```bash
$ phpcbf --standard=PSR12 ./src
```
#### Unit test
```bash
$ ./vendor/bin/phpunit Tests
$ XDEBUG_MODE=coverage ./vendor/bin/phpunit Tests --coverage-html coverage
```
Don't forget to create an .env file and edit 



## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
