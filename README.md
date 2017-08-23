# PHP Composter Regular Expressions (WIP)

### PHP Composter regular expression checks.

[![Latest Stable Version](https://poser.pugx.org/php-composter/php-composter-regular-expressions/v/stable)](https://packagist.org/packages/php-composter/php-composter-regular-expressions)
[![Total Downloads](https://poser.pugx.org/php-composter/php-composter-regular-expressions/downloads)](https://packagist.org/packages/php-composter/php-composter-regular-expressions)
[![Latest Unstable Version](https://poser.pugx.org/php-composter/php-composter-regular-expressions/v/unstable)](https://packagist.org/packages/php-composter/php-composter-regular-expressions)
[![License](https://poser.pugx.org/php-composter/php-composter-regular-expressions/license)](https://packagist.org/packages/php-composter/php-composter-regular-expressions)

This Composer package will check your commit messages to make sure they match a set of regular expression rules.

This is a [PHP Composter](https://github.com/php-composter/php-composter) Action.

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Contributing](#contributing)

## Installation

First, you need to add the package as a development requirement to your `composer.json`:

```BASH
composer require --dev php-composter/php-composter-regular-expressions
```

Then, you need to add the regular expressions you want to check for. As an example, the following expression will ensure that commit message subject lines start with one of the following words: `feature`, `bug`, `documentation`, `style`:

```JSON
"extra": {
  "php-composter-regular-expression": {
    "commit-message": {
      "subject": {
        "has": "^(feature|bug|documentation|style):"
      }
    }
  }
}
```

## Basic Usage

It should just work when you `git commit`.

## Contributing

All feedback / bug reports / pull requests are welcome.
