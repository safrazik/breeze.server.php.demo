# Breeze PHP Server Demo

### How to run the demo locally

- clone the repo and place the files into web accessible directory
- run `composer update`
- if you are using MySQL or any other Doctrine supported DBMS, change the settings
  in `bootstrap.php` file, and run [doctrine command](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/tools.html) `orm:schema-tool:create`
- open `client/index.html` to see the demo app

### Live demo

- http://breezedemo.herokuapp.com/client/index.html
- Open developer tools in Chrome (Shortcut: F12) and go to `Network` tab to monitor the request and response from server