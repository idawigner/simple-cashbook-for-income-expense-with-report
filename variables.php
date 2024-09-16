<?php
// Path from the root directory of server to htdocs/public_html, ONLY USE in includes/require statements of PHP
// Path from the root directory of domain/host

// Defining Global Variables
// APP_ROOT & APP_URL for Live Session
// define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/admin');
// const APP_URL = 'https://adreesch.com/admin';

// APP_ROOT & APP_URL for Local Session
define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/cashbook');
const APP_URL = 'http://localhost/cashbook';

// APP_NAME
const APP_NAME = 'CashFlow App';