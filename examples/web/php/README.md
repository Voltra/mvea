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



Once this is done you can then run `dbmate up` to apply all DB migrations.

Then you will have to install PHP dependencies using `composer install`.

Now that everything is set up, you only need to create a VirtualHost that points to the`public_html` directory.



## Description

