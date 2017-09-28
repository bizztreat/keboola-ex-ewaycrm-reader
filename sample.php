<?php
/**
 * Created by PhpStorm.
 * User: davidch
 * Date: 28/09/17
 * Time: 09:52
 */
$fhIn = fopen('/data/in/tables/source.csv', 'r');
$fhOut = fopen('/data/out/tables/destination.csv', 'w');

$header = fgetcsv($fhIn);
$numberIndex = array_search('number', $header);
fputcsv($fhOut, array_merge($header, ['double_number']));

while ($row = fgetcsv($fhIn)) {
    $row[] = $row[$numberIndex] * 2;
    fputcsv($fhOut, $row);
}

fclose($fhIn);
fclose($fhOut);
echo "All done";