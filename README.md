# YeastDatabase
A symfony PHP/MySQL application to track your yeast collection.

## Installation

Clone the repository and install the dependencies

~~~bash
git clone https://github.com/manulera/YeastDatabase
cd YeastDatabase
# Install php dependencies
composer install
# Install javascript dependencies
yarn install
yarn encore dev
# Initialize the database
bash bin/restart_database.sh
# Serve with symfony
symfony server:start
~~~

