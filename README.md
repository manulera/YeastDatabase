# YeastDatabase
A symfony PHP/MySQL web application to track your yeast collection.

This is just a prototype to illustrate the kind of information that one could access by tracking the provenance of strains. For example, see which strains contain a given allele, which alleles exist for a given locus, etc.

You can find a hosted version of this at [https://prototype.genestorian.org/](https://prototype.genestorian.org/).

This web application was a prove of concept and will not be developed further.

A video illustrating how to use the application can be found [here](https://www.youtube.com/watch?v=34GMuHpl7f0). To install the application see below.

## Installation

Working with php 7.4, symfony 4.14, yarn 1.22. Clone the repository and install the dependencies

As is, it uses sqlite, but you can change to mysql by editing the `.env` file.

~~~bash
git clone https://github.com/manulera/YeastDatabase
cd YeastDatabase
# Install php dependencies
composer install
# Install javascript dependencies
yarn install
yarn encore dev
# Initialize the database (create sqlite file and load the schema)
bash bin/restart_database.sh
# Serve with symfony
symfony server:start
~~~

