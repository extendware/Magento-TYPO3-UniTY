# Magento to TYPO3 Connector: Mage UniTY for Magento 2 and TYPO3
The Mage UniTY extension, developed by web-vision, allows seamless integration of the TYPO3 content management system with an existing Magento 2.x shop.
This integration enhances search service optimization, improves visitor experience, and facilitates professional content marketing for your Magento store.
Use TYPO3 as content mamagement system inside or side-by-side for your Magento sore. 

Checkout the video here: https://youtu.be/q6b1Eg8bS7k

## System Requirements
- A working TYPO3 CMS v11 LTS System with the TYPO3 Extension of the Magento-TYPO3-UniTY, which can be found here: https://github.com/web-vision/Magento-TYPO3-UniTY/
- A Magento 2.4.x version 

## Setup Mage UniTY for Magento.

- Unzip the zip file in app/code/WebVision
- Enable the module by running php bin/magento module:enable WebVision_Unity
- Apply database updates by running php bin/magento setup:upgrade*
- Flush the cache by running php bin/magento cache:flush

## Adding New Connection Parameters for TYPO3 Database in `app/etc/env.php`
To establish a connection to the TYPO3 database in Magento 2, you need to add new connection parameters to the app/etc/env.php file. Here is an example of how these parameters should be configured:
```
'db' => [
    'table_prefix' => '',
    'connection' => [
        'default' => [
            // Default Magento database connection parameters
            // ...
        ],
        'typo3' => [
            // TYPO3 database connection parameters
            'host' => '127.0.0.1:54030',
            'dbname' => 'db',
            'username' => 'db',
            'password' => 'db',
            'model' => 'mysql4',
            'engine' => 'innodb',
            'initStatements' => 'SET NAMES utf8;',
            'active' => '1',
            'driver_options' => [
                1014 => false
            ]
        ]
    ]
],
```
### Further documentation
Further documentation can be found here: https://docs.extendware.com/unity/index.html