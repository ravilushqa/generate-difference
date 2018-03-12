# project-lvl2-s197
[![Build Status](https://travis-ci.org/ravilushqa/project-lvl2-s197.svg?branch=master)](https://travis-ci.org/ravilushqa/project-lvl2-s197)
[![Maintainability](https://api.codeclimate.com/v1/badges/ed4735dd0590583298db/maintainability)](https://codeclimate.com/github/ravilushqa/project-lvl2-s197/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ed4735dd0590583298db/test_coverage)](https://codeclimate.com/github/ravilushqa/project-lvl2-s197/test_coverage)

This project can generate difference between two files.
## Installation
1. For CLI
```bash
composer global require ravilushqa/generate-difference
```

2. In your project
```bash
composer require ravilushqa/generate-difference
```

## Usage
### 1. For CLI
```bash
Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]
```
#### 2. For project
```php
//todo
```
## Supported formats
### 1. Formats of input files
* yml
* json
### 2. Formats of report
* plain
* pretty
* json

## Examples output
### Input files

**before.json**
```json
{
  "common": {
    "setting1": "Value 1",
    "setting2": "200",
    "setting3": true,
    "setting6": {
      "key": "value"
    }
  },
  "group1": {
    "baz": "bas",
    "foo": "bar"
  },
  "group2": {
    "abc": "12345"
  }
}
```
**after.json**
```json
{
  "common": {
    "setting1": "Value 1",
    "setting3": true,
    "setting4": "blah blah",
    "setting5": {
      "key5": "value5"
    }
  },

  "group1": {
    "foo": "bar",
    "baz": "bars"
  },

  "group3": {
    "fee": "100500"
  }
}
```

**result**

### Plain
```plain
Setting "common.setting2" deleted.
Setting "common.setting4" added with value "blah blah".
Setting "group1.baz" changed from "bas" to "bars".
Section "group2" deleted.
```

### Pretty
```pretty
{
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
    }
}
