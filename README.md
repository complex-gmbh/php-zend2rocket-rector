# Zend2Rocket Rector set

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
Rocket application muss erstellt werden.

### Run Rector

```
vendor/bin/rector process application/B2B/controllers/ -c vendor/complex/php-zend2rocket-rector/config/zend2rocket-controller.yaml -n
```
remove `-n` parameter to disable dry-run. Attention: files get modified

### Manual steps after execution

- check for return statement warnings in console output. Each one has to be adressed manually. The dev has to decide wether to replace the `return` with `return $this->currentZendViewResult();` or leave them as they are. Rector already inserted a new return-statement at the end of the method. This might be wrong or resulted in dead code because of a double return and should possibly be removed.
- empty ClassMethod: for every empty ClassMethod Warning `return $this->currentZendViewResult();` has to be inserted manually.
do the necessary manual steps //TODO
- check git diff carefully before committing

## Unittests
```
vendor/bin/phpunit tests/
```
