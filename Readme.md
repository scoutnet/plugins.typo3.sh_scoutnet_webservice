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

Author
------
If you have any questions reganding this software, you can send me an email to muetze@scoutnet.de

License
-------
(c) 2016 Stefan "Mütze" Horst <muetze@scoutnet.de>
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
