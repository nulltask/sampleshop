<?php

chdir(dirname(__FILE__));
require_once '../app/Sampleshop_Controller.php';

ini_set('max_execution_time', 0);

Sampleshop_Controller::main_CLI('Sampleshop_Controller', 'import_item');

