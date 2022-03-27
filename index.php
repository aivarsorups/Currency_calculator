<?php

/**
 * This is the main file which receives and analyzes data, 
 * generates response data and finally calls the template.
 */
// show all warnings and errors on the screen
error_reporting(E_ALL);
ini_set('display_errors', 1);

$currencies = array("USD" => "US dollar",
    "JPY" => "Japanese yen",
    "BGN" => "Bulgarian lev",
    "CZK" => "Czech koruna",
    "DKK" => "Danish krone",
    "GBP" => "Pound sterling",
    "HUF" => "Hungarian forint",
    "PLN" => "Polish zloty",
    "RON" => "Romanian leu",
    "SEK" => "Swedish krona",
    "CHF" => "Swiss franc",
    "ISK" => "Icelandic krona",
    "NOK" => "Norwegian krone",
    "HRK" => "Croatian kuna",
    "RUB" => "Russian rouble",
    "TRY" => "Turkish lira",
    "AUD" => "Australian dollar",
    "BRL" => "Brazilian real",
    "CAD" => "Canadian dollar",
    "CNY" => "Chinese yuan renminbi",
    "HKD" => "Hong Kong dollar",
    "IDR" => "Indonesian rupiah",
    "ILS" => "Israeli shekel",
    "INR" => "Indian rupee",
    "KRW" => "South Korean won",
    "MXN" => "Mexican peso",
    "MYR" => "Malaysian ringgit",
    "NZD" => "New Zealand dollar",
    "PHP" => "Philippine peso",
    "SGD" => "Singapore dollar",
    "THB" => "Thai baht",
    "ZAR" => "South African rand");

function findInFile($currency, $date) {
    $value = 0;
    $file = "xml/$currency.xml";
    if (file_exists($file)) {
        $response_xml_data = file_get_contents($file);
        $data = simplexml_load_string($response_xml_data);
        if ($data == "") {
            return putDataInFile($currency, $date);
        }
        foreach ($data->DataSet->Series->Obs as $a) {
            if ($a['TIME_PERIOD'] == $date) {
                return $value = $a['OBS_VALUE'];
            }
        }if ($value != 0) {
            return $value;
        } else {
            return putDataInFile($currency, $date);
        }
    } else {
        return putDataInFile($currency, $date);
    }
}

function putDataInFile($currency, $date) {
    echo " create file";
    $xml_url = "https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/$currency.xml";

    if (($response_xml_data = file_get_contents($xml_url)) === false) {
        echo "Error fetching XML\n";
    } else {
        libxml_use_internal_errors(true);
        $data = simplexml_load_string($response_xml_data);
        if ($data) {
            foreach ($data->DataSet->Series->Obs as $a) {
                if ($a['TIME_PERIOD'] == $date) {
                    $file = "xml/$currency.xml";
                    file_put_contents($file, $response_xml_data);
                    $current = file_get_contents($file);
                    $data = simplexml_load_string($response_xml_data);
                    return $a['OBS_VALUE'];
                }
            }
        }
    }
}

function validator($amount, $date, $name) {
    $result_message = "";
    if (!is_numeric($amount)) {
        $result_message .= "No numeric amounth! ";
    };
    if (empty($name)) {
        $result_message .= "Name is empty!";
    }
    if (($date < "1999-01-04")or($date > date("Y-m-d"))) {
        $result_message .= " $date Incorrect date! ";
    } else {
        checkIfDayIsWeekend($date);
    }
    return $result_message;
}

function multiplicator($amount, $curr) {
    if ($curr != null) {
        return round($amount / $curr, 2);
    } else {
        return "Information isn't available!";
    }
}

function checkIfDayIsWeekend($pdate) {///chek if a day is weekend 
    $MyGivenDateIn = strtotime($pdate);
    $ConverDate = date("l", $MyGivenDateIn);
    $ConverDateTomatch = strtolower($ConverDate);
    
    if ($ConverDateTomatch == "saturday") {
        $time_add = $MyGivenDateIn - (3600 * 24); //add seconds of one day
        $new_date = date("Y-m-d", $time_add);
        return $new_date;
    } elseif ($ConverDateTomatch == "sunday") {
        $time_add = $MyGivenDateIn - (3600 * 24 * 2); //add seconds of two days
        $new_date = date("Y-m-d", $time_add);
        echo $new_date;
        return $new_date;
    } else
        return $pdate;
};

$result = "";

if (isset($_GET['amount'])) {
    $amount = htmlspecialchars($_GET['amount']);
} else {
    $_GET['amount'] = 1;
    $amount = 1;
}
if (isset($_GET['date'])) {
    $fillName = date_format(date_create(htmlspecialchars($_GET['date'])), "Y-m-d");
    $date = checkIfDayIsWeekend($fillName);
} else {
    $date = "2021-03-08";
}
if (isset($_GET['currency'])) {
    $name = strtolower(htmlspecialchars($_GET['currency']));
} else {
    $_GET['currency'] = "USD";
    $name = strtolower(htmlspecialchars($_GET['currency']));
}

$result_message = validator($amount, $date, $name);

if ($result_message == "") {
    $result = "OK";

    $result_message = (multiplicator($amount, findInFile($name, $date)));
    if ($result_message == "Information isn't available!") {
        $result = "ERROR";
    };
} else {
    $result = "ERROR";
}

require("view.php");
