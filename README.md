# factorial/twigcs

Custom twigcs rule extending offical twics rules.


## Installation

Until this repository is available on packagist, please add the project manually into composer.json
Please refer https://getcomposer.org/doc/05-repositories.md to learn more about adding custom repositories to the project.
```
{
    ...
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:factorial-io/twigcs.git"
        }
    ],
```

```
composer require factorial-io/twigcs
```

## Usage

```bash
./vendor/bin/twics /path/to/views/ --ruleset Factorial\\twigcs\\TwigCsRuleset
```