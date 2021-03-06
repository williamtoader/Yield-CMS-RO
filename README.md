# Yield CMS

Alpha v0.2  
[![Gitter](https://badges.gitter.im/YieldCMS/community.svg)](https://gitter.im/YieldCMS/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

Vă rog să puneți la issues orice bug găsit și orice sugestie pe care o aveți.

## Configurarea bazei de date
Parametrii de conectare ai bazei de date MySQL/MariaDB se
vor seta în config.php.

După care din consola bazei de date se vor da următoarele interogări.
```sql
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `link` varchar(256) DEFAULT NULL,
  `data` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `username` varchar(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `data` varchar(1024) DEFAULT NULL,
  UNIQUE KEY `users_username_uindex` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
## Adăugarea utilizatorilor
**⚠Important** Fișierul setup.php trebuie șters dacă Yield este folosit în producție
Se va rula setup.php în consolă.

## Cookie-uri
În config.php se va seta domeniul pe care rulează Yield pentru cookie-urile 
de sesiune http-only.
## Conexiune Google Drive
În fișierul 
`ncld_plugin_manager/plugins/gdrive_generator/credentials.json`
se vor pune credențialele Google Cloud Platform Quickstart obținute 
de pe `https://developers.google.com/drive/api/v3/quickstart/php`
dând click pe **Enable the Drive API**.
După aceea se va rula scriptul `ncld_plugin_manager/plugins/gdrive_generator/gdrive_connect.php`
în consolă astfel se va obține tokenul de cont care se va pune în
`ncld_plugin_manager/plugins/gdrive_generator/token.json`.

## Plugin-uri
[Articol wiki](https://github.com/williamtoader/Yield-CMS-RO/wiki/Plugins#plugin-uri-de-backend)  

## Site-uri care folosesc Yield  
[onb2020.ro](https://www.onb2020.ro)
  
## Resurse externe  
Backend:
Am folosit API-urile Google pentru php. Sunt incluse și dependency-urile incluse de composer.  
  
Frontend:  
- Bootstrap  
- Jquery  
- Jquery UI
- Jquery UI touch punch
- Sheet JS  
- Fontawesome
- [Js-Toast](https://github.com/mlcheng/js-toast)
- bcrypt.js
- md5.js
