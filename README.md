# Magento 2 Module Customer Avatar

    composer require ghoster/module-customeravatar

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/thinghost)
[![Build Status](https://travis-ci.org/tuyennn/magento2-customer-avatar.svg?branch=master)](https://travis-ci.org/tuyennn/magento2-customer-avatar)
![Version 1.0.0](https://img.shields.io/badge/Version-1.0.0-green.svg)

---
## [![Alt GhoSter](http://thinghost.info/wp-content/uploads/2015/12/ghoster.png "thinghost.info")](http://thinghost.info) Overview

- [Extension on GitHub](https://github.com/tuyennn/magento2-customer-avatar)
- [Direct download link](https://github.com/tuyennn/magento2-customer-avatar/tarball/master)

![Alt Screenshot-1](http://thinghost.info/wp-content/uploads/2015/12/Selection_089-1000x746.png "thinghost.info")
![Alt Screenshot-2](http://thinghost.info/wp-content/uploads/2015/12/Selection_090-1000x578.png "thinghost.info")

## Main Functionalities
- Add a customer avatar as a customer attribute on customer edit, header.
- Able to upload as a simple attribute admin-end.
- Private content for loading avatar on header.
- Modern uploader from customer edit page.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/GhoSter`
 - Enable the module by running `php bin/magento module:enable GhoSter_CustomerAvatar`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require ghoster/module-customeravatar`
 - enable the module by running `php bin/magento module:enable GhoSter_CustomerAvatar`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

- Enable Header: Enable avatar on header


## Specifications

- `avatar` as customer attribute


## Attributes

 - Customer - Avatar (avatar)

