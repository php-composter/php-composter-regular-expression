# PHP Composter Regular Expressions (WIP)

### PHP Composter regular expression checks.

[![Latest Stable Version](https://poser.pugx.org/php-composter/php-composter-regular-expression/v/stable)](https://packagist.org/packages/php-composter/php-composter-regular-expression)
[![Total Downloads](https://poser.pugx.org/php-composter/php-composter-regular-expression/downloads)](https://packagist.org/packages/php-composter/php-composter-regular-expression)
[![Latest Unstable Version](https://poser.pugx.org/php-composter/php-composter-regular-expression/v/unstable)](https://packagist.org/packages/php-composter/php-composter-regular-expression)
[![License](https://poser.pugx.org/php-composter/php-composter-regular-expression/license)](https://packagist.org/packages/php-composter/php-composter-regular-expression)

This Composer package will check your commit messages to make sure they match a set of regular expression rules.

This is a [PHP Composter](https://github.com/php-composter/php-composter) Action.

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Contributing](#contributing)

## Installation

First, you need to add the package as a development requirement to your `composer.json`:

```BASH
composer require --dev php-composter/php-composter-regular-expression
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

## Composer Extra Reference

The Composer extra entry takes the following format:
```JSON
"extra": {
  "php-composter-regular-expression": {
    "<hook to check the regular expression on>": {
      "<content element to check>": {
        "<rule>": "<regular expression>"
      }
    }
  }
}
```

Each hook can contain multiple content elements to check, and each content element can contain multiple rules.

The regular expression will be wrapped in special delimiter chars (`chr(1)`), so you don't need to include these.

### Supported hooks

**`commit-message`:**

Check the commit message after it has been submitted.

### Supported content elements

#### For the `commit-message` hook

**`subject`:**

Subject line of the commit message, meaning the very first line of the commit message up until the first EOL character.

**`body`:**

Body of the commit message, meaning everything after the first EOL character.

### Supported rules

**`has`:**

Passes the check if the regular expression results in one or more matches.

**`has-not`:**

Passes the check if the regular expression results in no matches.

## Contributing

All feedback / bug reports / pull requests are welcome.
