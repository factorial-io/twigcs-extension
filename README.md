# factorial-io/twigcs-extension

<a href="https://github.com/factorial-io/twigcs-extension/actions?query=workflow%3Aci+branch%3Amaster"><img alt="Build status" src="https://github.com/factorial-io/twigcs-extension/workflows/ci/badge.svg?branch=master"></a>

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
