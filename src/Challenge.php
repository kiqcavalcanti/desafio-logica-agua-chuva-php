<?php

namespace App;

use Exception;

class Challenge
{

  /**
   * @param array $fileLines
   * @return array
   * @throws Exception
   */
  public function execute(array $fileLines): array
  {
    $output = [];
    $cases = $this->getCases($fileLines);

    foreach ($cases as $case) {
      $gaps = $this->findGaps($case);

      $output[] = $this->calculateGaps($gaps);
    }

    return ['input' => $fileLines, 'output' => $output];
  }

  protected function findGaps($heights): array
  {
    $gapsGroup = [];

    $heightIndex = 0;

    while ($heightIndex < count($heights)) {
      $heightValue = $heights[$heightIndex];
      $gaps = [];

      for ($nextHeightIndex = $heightIndex + 1; $nextHeightIndex < count($heights); $nextHeightIndex++) {
        $nextHeightValue = $heights[$nextHeightIndex];

        if ($nextHeightValue < $heightValue) {
          $gaps[] = $nextHeightValue;
          $heightIndex++;
        } else {
          break;
        }
      }


      $gapsGroup[] = ['startGap' =>  $heightValue, 'gaps' => $gaps];


      $heightIndex++;
    }

    return $gapsGroup;
  }

  protected function calculateGaps($gapsGroup): int {
    $sum = 0;

    foreach ($gapsGroup as $index => $gapGroup) {

      $startGap = $gapGroup['startGap'];
      $isLastIndex = count($gapsGroup) - 1 === $index;

      foreach ($gapGroup['gaps'] as $height) {

        if(!$isLastIndex) {
          $sum += $startGap - $height;
          continue;
        }

        $maxHeight = max($gapGroup['gaps']);

        if($height === $maxHeight) {
          break;
        }

        $sum += $maxHeight - $height;
      }
    }

    return $sum;
  }

  public function getCases($lines): array
  {
    $lineNumber = 0;
    $cases = [];

    foreach ($lines as $line) {
      $lineNumber++;
      $line = trim($line);

      if ($lineNumber === 1) {
        if (!is_numeric($line) || (int)$line < 1 || (int)$line > 100) {
          throw new Exception("Invalid number of cases on line 1.");
        }
        continue;
      }

      if ($lineNumber % 2 === 0) {

        if (!is_numeric($line) || (int)$line < 1) {
          throw new Exception("Invalid array size on line $lineNumber.");
        }
        $expectedSize = (int)$line;
      } else {
        $array = explode(" ", $line);
        if ($expectedSize && count($array) !== $expectedSize) {
          throw new Exception("The array on line $lineNumber does not match the expected size ($expectedSize).");
        }
        foreach ($array as $value) {
          if (!is_numeric($value)) {
            throw new Exception("Invalid value in the array on line $lineNumber.");
          }
        }
        $cases[] = array_map('intval', $array);
      }
    }

    if(count($cases) !== (int) $lines[0]) {
      throw new Exception("Invalid number of cases defined in line 1, expected: $lines[0]. current cases: " . count($cases));
    }

    return $cases;
  }

}