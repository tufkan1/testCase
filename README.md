# Simulation
## Getting Started

Clone the project:

```
> git clone https://github.com/tufkan1/testCase.git
```

### Prerequisites

for running the project you need the minimum requirement of running laravel 11 and there is no other third party packages


### Installing
```
> navigate to project
> composer install
> php -r "file_exists('.env') || copy('.env.example', '.env');"
> create a mysql database and add your database access in .env
> php artisan key:generate
> php artisan migrate --seed
```
