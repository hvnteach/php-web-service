<?php
require_once 'ServiceExam.php';
require_once 'inc.php';

$pro = Utils::parseUri();
$act = $pro['act'];
$type = $pro['type'];
$sub = $pro['sub'];

$service = new ServiceExam();
$func = 'get'.ucfirst(strtolower($sub)).ucfirst(strtolower($act));

$service->{$func}();
?>