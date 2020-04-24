[![Build Status](https://jenkins.scoutnet.eu/buildStatus/icon?job=scoutnet/plugins.typo3.sh_scoutnet_webservice/master)](https://jenkins.scoutnet.eu/job/scoutnet/job/plugins.typo3.sh_scoutnet_webservice/job/master/)
[![Packagist](https://img.shields.io/packagist/v/scoutnet/sh-scoutnet-webservice.svg)](https://packagist.org/packages/scoutnet/sh-scoutnet-webservice)
[![Packagist](https://img.shields.io/packagist/dt/scoutnet/sh-scoutnet-webservice.svg?label=packagist%20downloads)](https://packagist.org/packages/scoutnet/sh-scoutnet-webservice)
[![Packagist](https://img.shields.io/packagist/l/scoutnet/sh-scoutnet-webservice.svg)](https://packagist.org/packages/scoutnet/sh-scoutnet-webservice)
---
ScoutNet Webservice
===================

This Plugin is intended for the ScoutNet.de JSON RPC Api.

You need to register an account at ScoutNet.de and request an API key for your site.
To do so please send an email to scoutnetconnect@scoutnet.de

Install
-------
To install You can either use the version from the TER, or install this git repo to 

<TYPO3 Dir>/typo3conf/ext/sh_scoutnet_webservice


Setup
-----
You need to activate The Plugin and set the AES Key/IV and the correct provider name. 
You can find this Informations in the rights configuration of your Group.

https://www.scoutnet.de/community/rechte/rechte-verwalten.html

Update
------
If you update from a Version < 2.0 please note that the plugin got completely rewritten. It is now based on Extbase. Therefor the complete api changed.
With Extbase you can include the webservice by dependency injection. Some of the APIs are changed as well.

Development
-----------
If you want to contribute, feel free to do so. The Repo is located here:

https://github.com/scoutnet/plugins.typo3.sh_scoutnet_webservice

just run `make composerInstall`

Testing
-------
Needed: GnuMake, PHP, Docker and docker-compose

Init:

`make init`

To Run all the Tests call:

`make test`

you can use the -phpx suffix to indicate which php version you want to check e.g. `make test-php73`

for only testing a special function or php version there are different suffixes. For Example:

- `make lintTest-php73`
- `make unitTest-php73`
- `make unitTest`        Will call Unit tests with php7.3 and php 7.4

Testing with PhpStorm: Setup new remote PHP interpreter.
Docker-Compose:
 - compose file: `Tests/Build/docker-compose.yml`
 - service: ` functional_mariadb`
 
Set up new Test Framework:
 - path to phpunit: `bin/phpunit`
 - default config: `vendor/typo3/testing-framework/Resources/Core/Build/UnitTests.xml`
 - add path mappings: `<abs. Path to this dir>` -> `<abs. Path to this dir>` (all paths mapped like on your host)
 
Set up new Run Configuration for `Unit Tests`:
 - Test Scope: `<abs. Path to this dir>/Tests/Unit`
 - Custom Working Directory: `<abs. Path to this dir>/.Build/`
 
Set up new Run Configuration for `Functional Tests`:
 - Test Scope: `<abs. Path to this dir>/Tests/Functional`
 - Custom Working Directory: `<abs. Path to this dir>/.Build/`
 - Use alternative configuration File: `<aps. Path to this dir>/.Build/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTests.xml`
 - Environment variables: `typo3DatabaseUsername=root;typo3DatabasePassword=funcp;typo3DatabaseHost=mariadb10;typo3DatabaseName=func_test`
 
Happy Testing



Author
------
If you have any questions reganding this software, you can send me an email to muetze@scoutnet.de

License
-------
(c) 2020 Stefan "Mütze" Horst <muetze@scoutnet.de>
All rights reserved

This script is part of the TYPO3 project. The TYPO3 project is
free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

The GNU General Public License can be found at
http://www.gnu.org/copyleft/gpl.html.

This script is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

This copyright notice MUST APPEAR in all copies of the script!
