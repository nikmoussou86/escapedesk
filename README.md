# Escapedesk

Escapedesk is an application where employees can request their vacation online. The manager can then manage the requests and approve or reject them.

## Installation

**Step 1**: Clone the repo locally

```bash
git clone git@github.com:nikmoussou86/escapedesk.git
```
**Step 2**: Make sure you have docker compose installed in your machine and navigate to the `docker` folder of the project to download relative images and build the containers using below commands.

```bash
$ cd docker
$ docker-compose up -d
```
**Step 3**: Get inside the `mysql:8.0` container and create an empty database like that (use `root` when prompt for password):

```bash
$ docker exec -it db bash
$ mysql -u root -p

mysql> create database escapedesk;
```
**Step 4**: Copy the `.env.example` file of the root folder, rename it to `.env` and paste the below:
```
DB_HOST=db 
DB_USER=root
DB_PASS=root
DB_NAME=escapedesk
DB_PORT=3306
```
**Step 5**: Get inside the `docker-app` container and install packages using composer:

```bash
$ docker exec -it escapedesk-app bash
$ composer install
```
**Step 6**: In the same terminal inside the `escapedesk-app` container run the migrations:
```
$ ./vendor/bin/doctrine-migrations migrate
```
**Step 7**: Finally you navigate to the `scripts` folder and run the `users_seeder.php` script to insert 2 initial users of the app in the database. To do so execute the follow command inside `docker-app` container:
```
$ cd scripts
$ php users_seeder.php
```
Congrats! You made it!

## Usage

1. Open the browser and go to `http://localhost:8000`
2. You should see the login page
3. Login as a **Manager** using `manager@example.com | pass: securepass123` or as an **Employee** using `employee@example.com | pass: securepass123`
   
