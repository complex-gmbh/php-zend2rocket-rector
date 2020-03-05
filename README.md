# Zend2Rocket Rector set

### Installation
Global installation outside of the project is recommended with:
```
composer global require --dev rector/rector
composer require --dev complex/php-zend2rocket-rector:dev-master
```

### Autoloading

- copy the `autoload-rector.php` into your project root folder. This ensures all controllers could be autoloaded in the refactoring process
- check for correct paths in line 4-5. At least the tenant name in line 5 has to be changed to foldername accordingly.

### Rocket Application

Rocket Application has to be created by @clxmstaab

### Run Rector

```
vendor/bin/rector process application/B2B/controllers/ -c vendor/complex/php-zend2rocket-rector/config/zend2rocket-controller.yaml -n
```
remove `-n` parameter to disable dry-run. Attention: files get modified

### Manual steps after execution

- check for return statement warnings in console output. Each one has to be adressed manually. The dev has to decide wether to replace the `return` with `return $this->currentZendViewResult();` or leave them as they are. Rector already inserted a new return-statement at the end of the method. This might be wrong or resulted in dead code because of a double return and should possibly be removed.
- empty ClassMethod: for every empty ClassMethod Warning `return $this->currentZendViewResult();` has to be inserted manually.
- check git diff carefully before committing

### Cleanup

- remove `autoload-rector.php`. It's no longer needed
- remove `complex/php-zend2rocket-rector` dependency from project
- ALL DONE! All Controllers are now Rocket based.

## Unittests
```
vendor/bin/phpunit tests/
```
