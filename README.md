[![Build Status](https://jenkins.scoutnet.eu/buildStatus/icon?job=scoutnet/plugins.typo3.sh_scoutnet_webservice/main)](https://jenkins.scoutnet.eu/job/scoutnet/job/plugins.typo3.sh_scoutnet_webservice/job/main/)
[![codecov](https://codecov.io/gh/scoutnet/plugins.typo3.sh_scoutnet_webservice/graph/badge.svg?token=48MWySyCz1)](https://codecov.io/gh/scoutnet/plugins.typo3.sh_scoutnet_webservice)
[![Packagist](https://img.shields.io/packagist/v/scoutnet/sh-scoutnet-webservice.svg)](https://packagist.org/packages/scoutnet/sh-scoutnet-webservice)
[![Packagist](https://img.shields.io/packagist/dt/scoutnet/sh-scoutnet-webservice.svg?label=packagist%20downloads)](https://packagist.org/packages/scoutnet/sh-scoutnet-webservice)
[![Packagist](https://img.shields.io/packagist/l/scoutnet/sh-scoutnet-webservice.svg)](https://packagist.org/packages/scoutnet/sh-scoutnet-webservice)
---
# ScoutNet Webservice

This Typo3 plugin is intended for the ScoutNet.de JSON RPC Api.

You need to register an account at ScoutNet.de and request an API key for your site.
To do so please send an email to scoutnetconnect@scoutnet.de

## Installation
To install You can either use the version from the TER, or install this git repo to 

<TYPO3 Dir>/typo3conf/ext/sh_scoutnet_webservice

alternatively you can use composer:

`composer require scoutnet/sh-scoutnet-webservice`

### Setup
You need to activate The Plugin and set the AES Key/IV and the correct provider name. 
You can find this Informations in the rights configuration of your Group.

https://www.scoutnet.de/community/rechte/rechte-verwalten.html

## Development
If you want to contribute, feel free to do so. The Repo is located here:

https://github.com/scoutnet/plugins.typo3.sh_scoutnet_webservice

just run `make init`

### Testing

Needed: GnuMake, PHP, Docker and docker-compose

Init:

`make init`

To Run all the Tests call:

`make test`

you can use the -phpx suffix to indicate which php version you want to check e.g. `make test-php81`

for only testing a special function or php version there are different suffixes. For Example:

- `make lintTest-php81`
- `make unitTest-php83`
- `make unitTest`        Will call Unit tests with php 8.1 through php 8.3

For running only certain tests use:

- `TEST_FILE=$(pwd)/Tests/Functional/Plugins/WebringPluginTest.php EXTRA_TEST_OPTIONS='--filter testRedirect' make functionalTest-php83`

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
 - Test Runner options: `--coverage-filter <abs. Path to this dir>/Classes`

Set up new Run Configuration for `Functional Tests`:
 - Test Scope: `<abs. Path to this dir>/Tests/Functional`
 - Custom Working Directory: `<abs. Path to this dir>/.Build/`
 - Use alternative configuration File: `<aps. Path to this dir>/.Build/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTests.xml`
 - Test Runner options: `--coverage-filter <abs. Path to this dir>/Classes`
 - Environment variables: `typo3DatabaseUsername=root;typo3DatabasePassword=funcp;typo3DatabaseHost=mariadb10;typo3DatabaseName=func_test`

Happy Testing

#### Without docker:
CGL Test: `.Build/bin/php-cs-fixer fix -v --dry-run --diff --config=.Build/vendor/typo3/coding-standards/templates/extension
_php-cs-fixer.dist.php  --using-cache=no Classes/ Resources/ Tests/`

Unit Test: `.Build/bin/phpunit -c .Build/vendor/typo3/testing-framework/Resources/Core/Build/UnitTests.xml Tests/Unit`

### Update

#### 1.x->3.0
If you update from a Version < 2.0 please note that the plugin got completely rewritten. It is now based on Extbase. Therefore the complete api changed.
With Extbase you can include the webservice by dependency injection. Some of the APIs are changed as well.

#### 3.x->4.0
With the update there are a lot of Breaking changes. The Structures were renamed to use english names as well as use proper writing.

In Detail: 
```
Categorie -> Category
Stufe -> Section
```

With this the Repositorys are changed as well. The Section now knows about the Category Object and does not only store the CategoryID.

UserRepository->findByUid was renamed to findByUsername (since this is what it does)

#### 4.x->5.0
TBD

#### 5.x->6.0
TBD

### Author
If you have any questions regarding this software, you can send me an email to muetze@scoutnet.de

### TODO


### License
(c) 2020 Stefan "Mütze" Horst <muetze@scoutnet.de>
All rights reserved

This script is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

This copyright notice MUST APPEAR in all copies of the script!
