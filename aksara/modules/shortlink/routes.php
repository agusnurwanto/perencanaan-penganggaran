<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * extra routes
 */

$route['s'] = 'shortlink';
$route['s/(:any)'] = 'shortlink/$1';