<?php
session_start();
include '../vendor/autoload.php';

use App\Http\Request;

Request::handle();