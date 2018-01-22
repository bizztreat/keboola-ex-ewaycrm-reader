<?php
/**
 * Created by PhpStorm.
 * User: davidch
 * Date: 28/09/17
 * Time: 09:52
 */
require_once "eway/eway.class.php";

$NL = "\r\n";

$arguments = getopt("d::", array("data::"));

//print_r($arguments);
if (!isset($arguments["data"])) {
    print "Data folder not set.";
    exit(1);
}

try {
    $fileOut = fopen('/data/out/tables/destination.csv', 'w');
    if (!$fileOut) {
        print "File open failed.";
        exit(1);
    }

    $dataDir = $arguments["data"] . DIRECTORY_SEPARATOR;
    $configFile = $dataDir . 'config.json';

    $config = json_decode(file_get_contents($configFile), FILE_USE_INCLUDE_PATH);

    $webServiceAddress = $config['parameters']['webServiceAddress'];
    $username = $config['parameters']['username'];
    $password = $config['parameters']['#password'];
    $apiFunction = $config['parameters']['apiFunction'];
    $dieOnItemConflict = $config['parameters']['dieOnItemConflict'];
    $debugMode = false; //$config['parameters']['debug'];
    $passwordAlreadyEncrypted = false; //$config['parameters']['passwordAlreadyEncrypted'];

    print "version: 1.3.0" . $NL;
    print "host: " . $webServiceAddress . $NL;

    // Create eWay API connector
    $connector = new eWayConnector($webServiceAddress, $username, $password, $passwordAlreadyEncrypted, $dieOnItemConflict, $debugMode);

    switch ($apiFunction) {
        case "getCompanies":
            print "Reading Companies from eWay CRM ..." . $NL;
            $result = $connector->getCompanies();
            break;
        case "getProjects":
            print "Reading Projects from eWay CRM ..." . $NL;
            $result = $connector->getProjects();
            break;
        case "getInvoices":
            print "Reading Invoices from eWay CRM ..." . $NL;
            $result = $connector->getCarts();
            break;
        default:
            print "Unknown eWay API call! try: getCompanies, getProjects, getInvoices." . $NL;
            exit(1);
    }

    if ($result->ReturnCode == 'rcSuccess') {
        switch ($apiFunction) {
            case "getCompanies":
                print "Writing Companies to Keboola storage ..." . $NL;
                fputcsv($fileOut, ['ItemGUID', 'ItemVersion', 'IdentificationNumber', 'CompanyName', 'MRPID'], ',', '"');
                foreach ($result->Data as $record) {
                    fputcsv($fileOut, [
                        $record->ItemGUID,
                        $record->ItemVersion,
                        $record->IdentificationNumber,
                        $record->CompanyName,
                        isset($record->AdditionalFields->af_33) ? $record->AdditionalFields->af_33 : "" // MRPID
//                        $record->AdditionalFields->af_18 // MRPID trial
                    ], ',', '"');
                }
                break;
            case "getProjects":
                print "Writing Projects to Keboola storage ..." . $NL;
                fputcsv($fileOut, ['ItemGUID', 'ItemVersion', 'ProjectName', 'OrderNumber', 'MRPID'], ',', '"');
                foreach ($result->Data as $record) {
                    fputcsv($fileOut, [
                        $record->ItemGUID,
                        $record->ItemVersion,
                        $record->ProjectName,
//                        $record->AdditionalFields->af_26, // OrderNumber trial
                        isset($record->AdditionalFields->af_25) ? $record->AdditionalFields->af_25 : "", // OrderNumber
//                        $record->AdditionalFields->af_24 // MRPID trial
                        isset($record->AdditionalFields->af_34) ? $record->AdditionalFields->af_34 : ""// MRPID
                    ], ',', '"');
                }
                break;
            case "getInvoices":
                print "Writing Invoices to Keboola storage ..." . $NL;
                fputcsv($fileOut, ['ItemGUID', 'ItemVersion', 'InvoiceNumber', 'MRPID'], ',', '"');
                foreach ($result->Data as $record) {
                    fputcsv($fileOut, [
                        $record->ItemGUID,
                        $record->ItemVersion,
                        $record->FileAs, // InvoiceNumber
//                        $record->AdditionalFields->af_24??? // MRPID trial
                        isset($record->AdditionalFields->af_35) ? $record->AdditionalFields->af_35 : ""// MRPID
                    ], ',', '"');
                }
                break;
        }
    } else {
        print "Unable to get data: " . $result->Description . $NL;
        exit(1);
    }

    // Manifest
    $manifest->incremental = true;
    $manifest->primary_key = ["ItemGUID"];
    $fileOutManifest = fopen($dataDir . '/out/tables/destination.csv.manifest', 'w');
    fwrite($fileOutManifest, json_encode($manifest));

} catch (InvalidArgumentException $e) {
    print $e->getMessage();
    exit(1);
} catch (Exception $e) { // + $e
    print $e->getMessage();
    exit(1);
} finally {
    fclose($fileOut);
    fclose($fileOutManifest);
}

print "Processed " . count($result->Data) . " rows." . $NL;
exit(0);