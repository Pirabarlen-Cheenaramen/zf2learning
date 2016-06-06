# Sample simple project time management tool.
v1.0 - Can only add new project & perform simple time management for projects added. 

#using the dev webserver provided

- import projecttimer.sql in your database

- set your database login details in 
```
config/autoload/local.php
```
- used the dev webserver itself
```
php -S 0.0.0.0:8080 -t public/ public/index.php
```

- then visit:
```
http://localhost:8080/projecttimer
```
