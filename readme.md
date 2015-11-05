## Online Role Playing Game based on Laravel PHP Framework

###Table of Contents###

1. [Requirements](#requirments)
2. [Installation](#installation)
3. [Running in development environment](#running_in_development_environment)
4. [License](#license)

<a name="requirements"></a>
### Requirements (for local development)

- PHP 5.5.9 or Higher
- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [Vagrant](https://www.vagrantup.com/) or [XAMPP](https://www.apachefriends.org/index.html)

<a name="installation"></a>
### Installation
- Clone the repo

        git clone https://github.com/mchekin/rpg.git rpg
- Navigate to the project folder

        cd rpg
- Run composer install to import the dependencies and enable auto-loading

        composer install
- Create .env file from the .env.example file

        cp .env.example .env
- Generate Laravel Application key

        php artisan key:generate

<a name="runningindevelopmentenvironment"></a>
### Running in development environment
   
#### First option:  Vagrant (Recommended)

- Generate local Homestead files

        php vendor/bin/homestead make
- Boot the virtual machine

        vagrant up  
- Run Laravel database migrations

        php artisan migrate 
- Run PHP build-in development server

        php artisan serve  
- Navigate to [http://localhost:8000/](http://localhost:8000/)
              
#### Second option: Running web server using XAMPP 

- Place your project `rpg` directory into XAMPP's `xampp/htdocs` directory  


- Set XAMPP's database credentials and schema to fit the DB parameters in `.env` file. 
 
 
- Run Laravel database migrations

        php artisan migrate 

- Navigate to [http://localhost/rpg/public](http://localhost/rpg/public)    

<a name="license"></a>
### License
Open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
