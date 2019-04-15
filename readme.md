# LogiLotto game

LogiLotto game is project as part of technical interview.

---

## Getting Started

### Global Architecture

Main tasks were to create web based game where player can bet on predicting result.
Front end part is dynamic. Make sure to use 1920x1080 resolution. 

You can see diagram of API: 

![alt text](https://raw.githubusercontent.com/k4rz4/logilotto-game/master/LogiLottoArchitecture.jpg)

### Installation: 

Edit config file.

```
$ api/config/config.ini
```

Import database.

```
$ db_dump/logilotto.sql
```

Webroot should be
```
$ api/
```

#### Apache2 conf

 Make sure you have enabled module rewrite.
 
```
$ sudo e2emod cache
$ sudo a2enmod rewrite
```


---

### Starting the background process
```
$ php logilottoengine.php &
```
### Starting frontend

Navigate to ui/index.hml and start http-server.
My preference
```
$ npm install -g http-server
$ http-server
```
