<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

$route['dashboard'] = 'dashboard/index';
$route['reception/dashboard'] = 'dashboard/reception';
$route['opd'] = 'dashboard/opd';

$route['users'] = 'users/index';
$route['users/create'] = 'users/create';
$route['users/update/(:num)'] = 'users/update/$1';
$route['users/delete/(:num)'] = 'users/delete/$1';

$route['patients'] = 'patients/index';
$route['patients/create'] = 'patients/create';
$route['patients/update/(:num)'] = 'patients/update/$1';
$route['patients/add-diagnose-fee/(:num)'] = 'patients/addDiagnoseFee/$1';

$route['patient-payments/registration'] = 'patientpayments/registration';
$route['patient-payments/diagnose'] = 'patientpayments/diagnose';
$route['patient-payments/approve/(:num)'] = 'patientpayments/approve/$1';
$route['patient-payments/reject/(:num)'] = 'patientpayments/reject/$1';

$route['payments'] = 'payments/index';
$route['payments/create'] = 'payments/create';
$route['payments/update/(:num)'] = 'payments/update/$1';
$route['payments/delete/(:num)'] = 'payments/delete/$1';
$route['payments/settings'] = 'payments/settings';
