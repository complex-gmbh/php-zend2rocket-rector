# Zend2Rocket Rector set

### Installation
First change `platform php` version temporary to `7.3`
```
"platform" : {
            "php": "7.3"
}
```
Install `php-zend2rocket-rector`:
```
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

### Further manual steps (copied from @clxmstaab's receipe)
- `svn mv` the controller into the B2Brocket/controllers directory
- make sure all javascript related logic sends a `Accept: application/json` http header, e.g. using `jQuery.ajax(...dataType: 'json',...)`
- add a RewriteRule so your controller will be served via a rocket front-controller. E.g. in daiber append the common-rewrite-rules.conf with `RewriteRule ^/([a-z]{2})/modulecms/(.+) /www/www/daiber/public/B2Brocket/index.php [L]`

### Final Cleanup

- remove `autoload-rector.php`. It's no longer needed
- remove `complex/php-zend2rocket-rector` dependency from project
- change `Platform php` version back to its original (currently 7.0)
- ALL DONE! All Controllers are now Rocket based.

## Unittests
```
vendor/bin/phpunit tests/
```
