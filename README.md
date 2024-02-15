# Currency Manager Application

The Currency Manager Application is a web-based tool built with Laravel, designed to manage and track currencies. It allows users to add, edit, and view information about different currencies.

## Table of Contents

- [Features](#features)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Contributing](#contributing)
- [License](#license)
- [Acknowledgments](#acknowledgments)

## Features

- **Add Currency:** Add new currencies with their names and codes.
- **Edit Currency:** Modify existing currency information.
- **Search and Filter:** Quickly search and filter currencies by name or code.
- **Real-time Exchange Rates:** Display real-time exchange rates for each currency.

## Getting Started

### Prerequisites

Before you start, make sure you have the following installed:

- PHP
- Composer
- MySQL database

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/MabodiR/currency-manager.git

2. Install dependencies:

cd currency-manager
composer install
Configure the MySQL database:

3. Configure the environment:

Copy the .env.example file to .env.
Update the database configuration in the .env file.

4. Generate application key:

php artisan key:generate

5. Migrate and seed the database:
php artisan migrate --seed

6. Run the application
php artisan serve
The application will be available at http://localhost:8000.

###Usage
Navigate to the application in your web browser.
Use the "Add New Currency" button to add new currencies.
Click "Edit" next to a currency to modify its information.
Utilize the search and filter options for quick access.
Database Schema
The application uses the following database schema:

###Currencies Table
id: Currency ID (Primary Key)
name: Currency Name
code: Currency Code
created_at: Timestamp of creation
updated_at: Timestamp of the last update

###License
This project is licensed under the MIT License - see the LICENSE file for details.

###Acknowledgments
The application uses exchange rate data from ExchangeRate-API.
