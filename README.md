# php-zend2rocket-rector

### Installation
Global installation outside of the project is recommended with:
```
composer global require --dev complex/php-zend2rocket-rector
composer require --dev complex/php-zend2rocket-rector:dev-master
```

### Autoloading
make sure controller classes are autoloaded
TODO
### Rocket Appilcation erstellen
TODO

### Run Rector

```
vendor/bin/rector process application/B2B/controllers/ -c vendor/complex/php-zend2rocket-rector/config/zend2rocket-controller.yaml -n
```