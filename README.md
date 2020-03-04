# VDB Assessment for LW

### About this project
I have developed a one page application called `LW Comparator` using `Angular 9` for the frontend and `Symfony 5` for the backend.

The frontend was designed using Material Design components.

The `LW Comparator` App, using a backend, permits to a user to find a server that is a perfect match to satisfy his necessities. To help the user to find quickly a configuration, a form is present to perform searches across the data and then to select some product configuration to compare it by hiding other results.

A `Reset button` permits to restart with the product search. 

### Online example
Please visit http://lwcomparator.vincenzodibiaggio.net/

### Prerequisites to run locally the project and run tests

* PHP 7.2
* Node + npm
* docker + docker-compose
* Google chrome (to run tests e2e tests)
* Local ports `80`, `4200`, `3000` should be availables

### Setup

#### Backend (Symfony)
* Clone this repository
* Go to the project root and run `php composer.phar install`.

#### Frontend (Angular)
* Go to the project root 
* Move to the `nodefe` directory
* Run `npm install -g @angular/cli`
* Run `npm install`

#### Docker containers
* Go to the project root
* Run `docker-compose up -d`

Now the Backend Server is available at http://127.0.0.1/ and the Frontend App at http://127.0.0.1:4200/

### Tests

#### Backend
* Go to the project root
* Run `docker-compose run php /bin/bash`
* Run `vendor/codeception/codeception/codecept run` 

#### Frontend
* Go to the project root 
* Move to the `nodefe` directory
* run `ng e2e`
