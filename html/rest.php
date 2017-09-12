<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "../Classes/Rest.php";

$rest = new Rest();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if(isset($input)) {
    $posts = $input;
    $rest->getResponse($posts);
}

?>