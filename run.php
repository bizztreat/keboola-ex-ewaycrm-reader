<?php
/**
 * Created by PhpStorm.
 * User: davidch
 * Date: 28/09/17
 * Time: 09:52
 */
require_once "eway/eway.class.php";

$NL = "\r\n";

// Validation
$arguments = getopt("d::", array("data::"));
//print_r($arguments);
if (!isset($arguments["data"])) {
    print "Data folder not set.";
    exit(1);
}

$fhOut = fopen('/data/out/tables/destination.csv', 'w');

try {
    $dataDir = $arguments["data"] . DIRECTORY_SEPARATOR;
    $configFile = $dataDir . 'config.json';

    $config = json_decode(file_get_contents($configFile), FILE_USE_INCLUDE_PATH);

    $webServiceAddress = $config['parameters']['webServiceAddress'];
    $username = $config['parameters']['username'];
    $password = $config['parameters']['#password'];
    $passwordAlreadyEncrypted = false; //$config['parameters']['passwordAlreadyEncrypted'];
    $dieOnItemConflict = $config['parameters']['dieOnItemConflict'];
    $apiFunction = $config['parameters']['apiFunction'];

    print "host: " . $webServiceAddress . $NL;
    print "username: " . (! empty($username)) ? "*****" : "!!! EMPTY !!!" . $NL;
    print "password: " . (! empty($password)) ? "*****" : "!!! EMPTY !!!" . $NL;
    print "dieOnItemConflict: " . $dieOnItemConflict . $NL;
    print "apiFunction: " . $apiFunction . $NL;

    // Create eWay API connector
    $connector = new eWayConnector($webServiceAddress, $username, $password, $passwordAlreadyEncrypted, $dieOnItemConflict);

    switch ($apiFunction) {
        case "getCompanies":
            print "Reading Companies from eWay CRM ..." . $NL;
            $result = $connector->getCompanies();
            break;
        case "getProjects":
            print "Reading Projects from eWay CRM ..." . $NL;
            $result = $connector->getProjects();
            break;
        default:
            print "Unknown eWay API call! try: getCompanies, getProjects." . $NL;
            exit(1);
    }

    if ($result->ReturnCode == 'rcSuccess') {
        switch ($apiFunction) {
            case "getCompanies":
                print "Writing Companies to Keboola ..." . $NL;
                fputcsv($fhOut, ['ItemGUID', 'ItemVersion', 'IdentificationNumber', 'CompanyName', 'MRPID'], ',', '"');
                foreach ($result->Data as $record) {
                    fputcsv($fhOut, [
                        $record->ItemGUID,
                        $record->ItemVersion,
                        $record->IdentificationNumber,
                        $record->CompanyName,
                        $record->AdditionalFields->af_18 // MRPID
                    ], ',', '"');
                }
                break;
            case "getProjects":
                print "Writing Projects to Keboola ..." . $NL;
                fputcsv($fhOut, ['ItemGUID', 'ItemVersion', 'ProjectName', 'OrderNumber', 'MRPID'], ',', '"');
                foreach ($result->Data as $record) {
                    fputcsv($fhOut, [
                        $record->ItemGUID,
                        $record->ItemVersion,
                        $record->ProjectName,
                        $record->AdditionalFields->af_26, // OrderNumber
                        $record->AdditionalFields->af_24 // MRPID
                    ], ',', '"');
                }
                break;
        }
    } else {
        print "Unable to get data: " . $result->Description . $NL;
    }

} catch (InvalidArgumentException $e) {
    print $e->getMessage();
    exit(1);
} catch (\Throwable $e) { // + $e
    print $e->getMessage();
    exit(2);
}

fclose($fhOut);

print "Processed " . count($result->Data) . " rows." . $NL;
exit(0);