This is a trip sorter REST API built in php with vagrant and phpunit for tests and the rest calls tested with postman.

The objective was to write an API that lets you sort this kind of list and present back a description of how to complete your journey.

For instance the API should be able to take an unordered set of boarding cards, provided in a format defined by you, and produce ordered list.

Included in the repo:

The vagrant file
Composer json for phpunit
Postman collection json


To install:

1.Clone the repo

2.Pull up the vagrant machine with "vagrant up"

3.Install dependancy (phpunit) with "composer install"


To do REST call:

POST data to 127.0.0.1:8080/html/rest.php as per the postman json. 


To run unit test:

1.While the vagrant machine is running type command "vagrant ssh"

2. Make dure you are in the root directory of codebase and run "vendor/bin/phpunit"

