# PHP test

## 1. Installation

  - create an empty database named "phptest" on your MySQL server
  - import the dbdump.sql in the "phptest" database
  - put your MySQL server credentials in the constructor of DB class
  - you can test the demo script in your shell: "php index.php"

## 2. Expectations

This simple application works, but with very old-style monolithic codebase, so do anything you want with it, to make it:

  - easier to work with
  - more maintainable



With the additional details you've provided, I'll update the documentation accordingly. Since the project doesn't follow the MVC pattern, doesn't have an API, and doesn't include tests yet, I'll omit those sections. The instructions for running the application will also be updated to reflect that it's a command-line based script. Here's your revised documentation:

---

# PHP Test

A command-line PHP project designed for testing and demonstrating PHP functionalities.

## Description

This project serves as a template for PHP developers to experiment with PHP features and develop simple scripts that can be run in the terminal. It's structured with basic organizational practices in mind, utilizing Composer for dependency management and adhering to PSR standards.

## Getting Started

### Dependencies

- PHP 7.4 or higher
- Composer for dependency management and adhering to PSR-12 for autoloading
- A MySQL database


### Installing

To set up your local development environment, clone the repository:

```sh
git clone https://github.com/maorbenzvi356/php-test.git
```

Navigate to the project directory:

```sh
cd php-test
```

Install the required composer dependencies:

```sh
composer install
```

### Configuring the Database

Create a new MySQL database and import the dbdump.sql file provided in the repository to set up the necessary table structures. You can name the database as you prefer.

```sh
mysql -u username -p -e "CREATE DATABASE your_db_name;"
mysql -u username -p your_db_name < path/to/dbdump.sql
```

Make sure to replace username with your MySQL username, your_db_name with the name you choose for your database, and path/to/dbdump.sql with the actual path to the SQL file in the repository.

Once the database is set up, create a .env file based on the .env.example provided in the project to set up your environment variables:

```sh
cp .env.example .env
```

Edit the .env file with your database connection details, ensuring the DB_NAME matches the name of the database you created.

## Usage

### Running the application

To run the application, execute the `index.php` script from the command line:

```sh
php index.php
```

The output will be displayed in the terminal.
