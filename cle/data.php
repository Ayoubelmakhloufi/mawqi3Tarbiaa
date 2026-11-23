<?php
header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/../data/cle.json';

if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo json_encode([]);
}
