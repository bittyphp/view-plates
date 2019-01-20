# Plates View

[![Build Status](https://travis-ci.org/bittyphp/view-plates.svg?branch=master)](https://travis-ci.org/bittyphp/view-plates)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/134367bfacdb415c949591efde0eb9c9)](https://www.codacy.com/app/bittyphp/view-plates)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Total Downloads](https://poser.pugx.org/bittyphp/view-plates/downloads)](https://packagist.org/packages/bittyphp/view-plates)
[![License](https://poser.pugx.org/bittyphp/view-plates/license)](https://packagist.org/packages/bittyphp/view-plates)

A [Plates](http://platesphp.com/) template view for Bitty.

## Installation

It's best to install using [Composer](https://getcomposer.org/).

```sh
$ composer require bittyphp/view-plates
```

## Setup

### Basic Usage

```php
<?php

require(dirname(__DIR__).'/vendor/autoload.php');

use Bitty\Application;
use Bitty\View\Plates;

$app = new Application();

$app->getContainer()->set('view', function () {
    return new Plates(dirname(__DIR__).'/templates/', $options);
});

$app->get('/', function () {
    return $this->get('view')->renderResponse('index', ['name' => 'Joe Schmoe']);
});

$app->run();

```

### Options

```php
<?php

use Bitty\View\Plates;

$plates = new Plates(
    dirname(__DIR__).'/templates/',
    [
        // Sets the extension you want to use.
        'extension' => 'php',
    ]
);

```

### Multiple Template Paths

If you have templates split across multiple directories, you can pass in an array with the paths to load from. Each path must have a namespace set as the array key.

The first path in the array will be considered the default.

```php
<?php

use Bitty\View\Plates;

$plates = new Plates(
    [
        'public' => 'templates/',
        'admin' => 'admin/templates/',
    ]
);

$plates->render('admin::foo');
// Loads admin/templates/foo.php

$plates->render('bar');
// Loads templates/bar.php

```

## Advanced

If you need to do any advanced customization, you can access the Plates engine directly at any time.

```php
<?php

use Bitty\View\Plates;
use League\Plates\Engine;

$plates = new Plates(...);

/** @var Engine */
$engine = $plates->getEngine();

```
