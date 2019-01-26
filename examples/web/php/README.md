# MVEA - web - PHP

## Setup

Requirements are the following :

- a bash compatible terminal ([GitBash](https://gitforwindows.org/) is enough on windows)
- [PHP](http://php.net/) >= 7.1.1
- [MySQL](https://www.mysql.com/) (>= 5.7.14 ?)
- [Apache](https://httpd.apache.org/) (>= 2.4.23 ?)
- [Composer](https://getcomposer.org/download/) (latest)
- [DBMate](https://github.com/amacneil/dbmate)
- a way to start a local server

Open up a terminal in the root folder (`examples/web/php`in this case).



Once everything is started, you will need to create DBmate's `.env` file containing the following :

```ini
DATABASE_URL = "<vendor>://<username>:<password>@<address>:<port>/<database>"
```

For more information, please refer to the [usage guide of DBmate](https://github.com/amacneil/dbmate#usage).



Once this is done you can then run `dbmate up` to apply all DB migrations.

Then you will have to install PHP dependencies using `composer install`.

Now that everything is set up, you only need to create a VirtualHost that points to the`public_html` directory.



## Description

This is a fairly simple application that provides authentication, roles, privileges as well as independent administration.

It uses [Slim 3](https://packagist.org/packages/slim/slim) along with [Twig 2](https://packagist.org/packages/slim/twig-view) to ease the use of ***MVEA***. As mentioned on the homepage, in the world of web development, ***MVEA*** may also be referred to as ***MVRA***.

* **models** are stored in `dev/Models`
* **views** are stored in `dev/views`
* **endpoints/routes** are stored in `dev/routes`
* **actions** are stored in `dev/Actions`



The application also uses Slim's middlewares along with (composable) authorization filters in order to allow more flexibility and expressibility in the endpoints' code. 



The application provides registration, login and logout utilities as well as a users list, an admin dashboard that allows to easily manipulate users' data.



Here is the exhaustive list of endpoints w/ their requirements

* `GET /` homepage, visitors can login/register users can see the board and logout
* `GET /login` login utility, must be a visitor
* `POST /login` login logic, must be a visitor
* `GET /register` registration utility, must be a visitor
* `POST /register` registration logic, must be a visitor
* `GET /logout` logout utility, must be a user
* `GET /admin` admin dashboard, must be an admin
* `GET /admin/deleteUser/{uid}` user deletion utility, must be an admin