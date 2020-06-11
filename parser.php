<?php
include "config.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbName);;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . PHP_EOL);
}
echo "Connected successfully" . PHP_EOL;

// clear table countries before start new parsing
$sql = 'TRUNCATE TABLE countries';
if ($conn->query($sql) === TRUE) {
    echo "countries clear successfully".PHP_EOL;
} else {
    echo "Error: " . $sql . PHP_EOL . $conn->error;
}

// clear table voperators before start new parsing
$sql = 'TRUNCATE TABLE operators';
if ($conn->query($sql) === TRUE) {
    echo "operators clear successfully".PHP_EOL;
} else {
    echo "Error: " . $sql . PHP_EOL . $conn->error;
}


$operators = getMobileOperators();
$countries = getCountries();


// insert countries to database
foreach ($countries as $country) {
    $code = $country['code'];
    $iso = $country['iso'];
    $name = $country['name'];
    $idx = $country['idx'];
    $sql = "INSERT INTO countries (code, country_name, iso, idx)
        VALUES ('$code', '$name', '$iso', '$idx')";

    if ($conn->query($sql) === TRUE) {
        echo "New countries created successfully".PHP_EOL;
    } else {
        echo "Error: " . $sql . PHP_EOL . $conn->error;
    }
}

// insert operators to db
foreach ($operators as $key => $operatorCountry) {
    foreach ($operatorCountry as $operator) {
        $mcc = $operator['mcc'];
        $mnc = $operator['mnc'];
        $brand = $operator['brand'];
        $bands = $operator['bands'];
        $op = $operator['operator'];
        $sql = "INSERT INTO operators (mcc, mnc, brand, operator, bands, idx) 
        VALUES ('$mcc', '$mnc', '$brand', '$op', '$bands', '$key')";

        if ($conn->query($sql) === TRUE) {
            echo "New operator created successfully" . PHP_EOL;
        } else {
            echo "Error: " . $sql . PHP_EOL . $conn->error;
        }
    }
}

/**
 * Get throw country table and grab information
 * @return array countries
 */
function getCountries() {
    $countries = array();
    $dom = new DOMDocument();
    $dom->loadHTMLFile('http://en.wikipedia.org/wiki/Mobile_Network_Code');

    // get tables
    $tables = $dom->getElementsByTagName('table');
    $rows = $tables->item(1)->getElementsByTagName('tr');
    foreach ($rows as $key => $row) {
        if ($row) {
            $cols = $row->getElementsByTagName('td');
            $value = array(
                'code' => 'Unknown',
                'name' => 'Unknown',
                'iso' => 'Unknown',
                'idx' => 'Unknown'
            );
            if ($cols->item(0) && $cols->item(1) && $cols->item(2)) {
                // country code
                if ($cols->item(0)) {
                    $value['code'] = trim($cols->item(0)->nodeValue);
                }

                // country name
                if ($cols->item(1)) {
                    $value['name'] = trim(getCountryName($cols->item(1)));
                }

                // country ISO
                if ($cols->item(2)) {
                    $value['iso'] = trim($cols->item(2)->nodeValue);
                }
                // idx
                $value['idx'] = $value['name'] . " - " . $value['iso'];

                array_push($countries, $value);
            }
        }
    }
 return $countries;
}

/**
 * Go throw all tables by links and grab info about mobile operators
 * @return array of mobile operators by country key
 */
function getMobileOperators()
{
    // all links with mobile operators by countries
    $regionsUrl = array(
        strToValidUrl('https://en.wikipedia.org/wiki/Mobile_Network_Codes_in_ITU_region_2xx_(Europe)'),
        strToValidUrl('https://en.wikipedia.org/wiki/Mobile_Network_Codes_in_ITU_region_3xx_(North_America)'),
        strToValidUrl('https://en.wikipedia.org/wiki/Mobile_Network_Codes_in_ITU_region_4xx_(Asia)'),
        strToValidUrl('https://en.wikipedia.org/wiki/Mobile_Network_Codes_in_ITU_region_5xx_(Oceania)'),
        strToValidUrl('https://en.wikipedia.org/wiki/Mobile_Network_Codes_in_ITU_region_6xx_(Africa)'),
        strToValidUrl('https://en.wikipedia.org/wiki/Mobile_Network_Codes_in_ITU_region_7xx_(South_America)')
    );

    $operators = array();
    foreach ($regionsUrl as $region) {
        $countries = array(); // array for saving countries names

        $dom = new DOMDocument();
        $dom->loadHTMLFile($region);

        /** In html pages all countries names store in tag <h4>
         * Get all counties names and push it to our array
         */
        $countriesHeaders = $dom->getElementsByTagName('h4');
        foreach ($countriesHeaders as $key => $header) {
            // There are 2 span tag in h4. first with country name, second with edit link.
            $country = trim($header->getElementsByTagName('span')->item(0)->nodeValue);
            if ($country === "") {
                $country = trim($header->getElementsByTagName('span')->item(1)->nodeValue);;
            }
            array_push($countries, $country);
        };
        foreach ($countries as $country) {
            $operators[$country] = array();
        }

        // get all tables on page
        $countriesOperatorsTable = $dom->getElementsByTagName('table');

        // get info from tables
        foreach ($countriesOperatorsTable as $key => $countryTable) {
            $rows = $countryTable->getElementsByTagName('tr');
            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                if ($cols->item(0)) {
                    // MCC
                    $mcc = $cols->item(0)->nodeValue;
                    // MNC
                    $mnc = $cols->item(1) ? $cols->item(1)->nodeValue : 'Unknown';
                    // Brand
                    $brand = $cols->item(2) ? $cols->item(2)->nodeValue : 'Unknown';
                    //Operator
                    $operator = $cols->item(3) ? $cols->item(3)->nodeValue : 'Unknown';
                    // Bands (MHz)
                    $bands = $cols->item(5) ? $cols->item(5)->nodeValue : 'Unknown';
                    $values = array(
                        'mcc' => $mcc,
                        'mnc' => $mnc,
                        'brand' => $brand,
                        'operator' => $operator,
                        'bands' => $bands
                    );
                    array_push($operators[$countries[$key]], $values);
                }
            }
        }
    }
    return $operators;
}


/**
 * Create valid URL string from invalid URL string
 * @param $str - Invalid URL
 * @return string - Valid URL
 */
function strToValidUrl($str)
{
    $str = trim($str);
    list($main, $anchor) = explode('(', $str);
    if ($anchor) {
        return $main . urlencode('(' . $anchor);
    }
    return $main;
}

/**
 * Create correct country name
 * @param $cols
 * @return mixed
 */
function getCountryName($cols)
{
    $div = $cols->getElementsByTagName('div')->item(0);
    if ($div) {
        $cols->removeChild($div);
    }
    return $cols->nodeValue;
}

