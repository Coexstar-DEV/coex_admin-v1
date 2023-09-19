<?php
// Require composer autoload
require_once   '../mpdf/vendor/autoload.php';
// Create an instance of the class:
$config = [
    'mode' => '+aCJK', 
    // "allowCJKoverflow" => true, 
    "autoScriptToLang" => true,
    // "allow_charset_conversion" => false,
    "autoLangToFont" => true,
];
$mpdf = new \Mpdf\Mpdf($config);

// Write some HTML code:
$mpdf->WriteHTML('김 순전');

// Output a PDF file directly to the browser
$mpdf->Output();
