<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Challenge;

$challenge = new Challenge();

$fileData = file('input.txt', FILE_IGNORE_NEW_LINES);

try {
  $result = $challenge->execute($fileData);

  echo "Input Values:\n";
  foreach ($result['input'] as $index => $input) {
    echo "Input $index: $input\n";
  }

  echo "\n";

  echo "Output Values:\n";
  foreach ($result['output'] as $index => $output) {
    echo "Output $index: $output\n";
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
  echo "\n";
}
