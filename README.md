## Printer

Printer is platform to simplify output and reduce effort in coding. 

### Requirement

* PHP >= 7.0
* MariaDB
* gnu-libiconv (extensions)
* gd (extensions)
* mbstring (extensions)

### Install

* docker-compose

```xaml
printer:
    image: maranathachristianuniversity/printer:latest
    ports:
        - '80:80'
        - '4000:443'
    environment:
        SECRET_KEY: <RANDOM_STRING_HERE>
        HOOK: <CUSTOM_HOOKS_HERE>
        SLACK: <CUSTOM_SLACK_HOOKS>
        DB_TYPE: mysql
        DB_HOST: 172.17.0.1
        DB_USER: root
        DB_PASS: root
        DB_NAME: master
        DB_PORT: 3306
        DB_CACHE: 'false'
        INSTALLED: false
        LIMITATIONS: 100
        ENVIRONMENT: PROD
    networks:
        - services
```

> don't forget to import MariaDB/MySQL database from **bootstrap/anywhere.sql**

### Contributing

If you want to join to develop this project, free to open a issue or pull request.
