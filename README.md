# factorial/twigcs

Custom twigcs rule extending offical twics rules.


## Installation

Until this repository is available on packagist, please add the project manually into composer.json

```
{
    ...
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:factorial-io/twigcs-extension.git"
        }
    ],
    ...
```
In case you want to learn more about adding a custom project to composer.json, please refer official documenation at https://getcomposer.org/doc/05-repositories.md.

Run composer require to add the package to your project.
```
composer require factorial-io/twigcs
```

## Usage

```bash
./vendor/bin/twigcs /path/to/views/ --ruleset Factorial\\twigcs\\TwigCsRuleset
```
Where, `/path/to/views` is the folder to scan for the twig files.