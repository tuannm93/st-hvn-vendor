# Coding Standard for Sharing Tech Project
## Basic
+ Follow PSR-2 Coding Style
+ Follow the `how_to_develop_on_project.md`

## Naming convention
+ CamelCaseVariableName
```php
class ClassName {
    public function doSomething() {
        $dataModule = new DataModule();
    }
}
```
(TBD)

## Tools
+ PSR-2 coding style will be checked by [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
```bash
$ vendor/bin/phpcs --standard=phpcs.xml --extensions=php . # To check

$ vendor/bin/phpcbf --standard=phpcs.xml --extensions=php . # To auto fix some common issues
```
+ [PHP_Mess Detector](https://phpmd.org/)
```bash
$ vendor/bin/phpmd . text ruleset.xml --suffixes php --exclude node_modules,resources,storage,vendor,_ide_helper.php,.phpstorm.meta.php
$ vendor/bin/phpmd . html --reportfile phpmd.html ruleset.xml --suffixes php --exclude node_modules,resources,storage,vendor,_ide_helper.php,.phpstorm.meta.php # html report
```
+ Running Unit test
```bash
$ vendor/bin/phpunit
$ vendor/bin/phpunit --coverage-html st_code_coverage # With generate code coverage in html
```

