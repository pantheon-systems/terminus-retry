# Terminus Retry

[![CircleCI](https://circleci.com/gh/pantheon-systems/terminus-retry.svg?style=shield)](https://circleci.com/gh/pantheon-systems/terminus-retry)
[![Terminus v2.x - v3.x Compatible](https://img.shields.io/badge/terminus-2.x%20--%203.x-green.svg)](https://github.com/pantheon-systems/terminus-plugin-example/tree/2.x)

A simple plugin that gives the ability to retry failed WP-CLI and Drush commands.

## Usage

To use, add a `--define="retryLimit=N"` to any `remote:wp` or `remote:drush` command.

Example:

```shell
terminus remote:wp mysite.dev --define="retryLimit=1" -- user get 1
```

<!-- ## Installation

To install this plugin using Terminus 3:

```shell
terminus self:plugin:install terminus-retry
``` -->

## Testing

This example project includes four testing targets:

* `composer lint`: Syntax-check all php source files.
* `composer cs`: Code-style check.
* `composer unit`: Run unit tests with phpunit
* `composer functional`: Run functional test with bats

To run all tests together, use `composer test`.

Note that prior to running the tests, you should first run:

* `composer install`
* `composer install-tools`
