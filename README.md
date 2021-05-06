# factorial-io/twigcs-extension

![example workflow](https://github.com/factorial-io/twigcs-extension/actions/workflows/ci.yml/badge.svg?branch=master)


Custom twigcs ruleset extending `Official` twigcs ruleset.

## Installation

Run composer require to add the package to your project.
```
composer require factorial-io/twigcs-extension
```

## Usage

```bash
./vendor/bin/twigcs /path/to/views/ --ruleset Factorial\\twigcs\\TwigCsRuleset
```
Where, `/path/to/views` is the folder to scan for the twig files.
