# project-lvl2-s197
[![Build Status](https://travis-ci.org/ravilushqa/project-lvl2-s197.svg?branch=master)](https://travis-ci.org/ravilushqa/project-lvl2-s197)
[![Maintainability](https://api.codeclimate.com/v1/badges/ed4735dd0590583298db/maintainability)](https://codeclimate.com/github/ravilushqa/project-lvl2-s197/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ed4735dd0590583298db/test_coverage)](https://codeclimate.com/github/ravilushqa/project-lvl2-s197/test_coverage)
## Installation
1. For CLI
composer global require ravilushqa/generate-difference

2. In your project
composer require ravilushqa/generate-difference

## Usage
#### 1. For CLI

```bash
$ gendiff --format plain first-config.json second-config.json
Setting "common.setting2" deleted.
Setting "common.setting4" added with value "blah blah".
Setting "group1.baz" changed from "bas" to "bars".
Section "group2" deleted.
```
#### 2. For project
```php
//todo
```
## Supported formats
#### 1. Formats of input files
* yml
* json
#### 2. Formats of report
* plain
* pretty
* json
