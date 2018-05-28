<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

// save translations in memory
$translations = [];

// simulating user language on session
$session_lang = 'pt';

// load translations by language on demand
if (!function_exists('lazy_boot_translations')) {
    function lazy_boot_translations($lang) {
        global $translations;
        $filename = __DIR__ . "/lang/{$lang}.php";
        if (file_exists($filename)) {
            $translations[$lang] = (include $filename);
        }
    }
}

// translate util
if (!function_exists('__')) {
    function __($message) {
        global $session_lang;
        global $translations;

        if (!isset($translations[$session_lang])) {
            lazy_boot_translations($session_lang);
        }

        if (isset($translations[$session_lang][$message])) {
            return $translations[$session_lang][$message];
        }

        return $message;
    }
}

// demo
$min = 1;
$max = 5;
$input = 'Lorem Ipsum';

try {
    $xablauValidator = v::intVal()->positive();
    $xablauValidator->assert($input);
} catch (ValidationException $e) {
    print_r($e->setParam('translator', '__')->getFullMessage());
}
