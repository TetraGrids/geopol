<?php
// Database connection
// --- Database Connection --- \\
require('connection.php');

/*/ Database Structure 

CREATE TABLE `place_names` (
  `id` int DEFAULT NULL,
  `level` int DEFAULT NULL,
  `parent_utf8` text COLLATE utf8mb4_unicode_ci,
  `parent_ascii` text COLLATE utf8mb4_unicode_ci,
  `place_name_utf8` text COLLATE utf8mb4_unicode_ci,
  `place_name_ascii` text COLLATE utf8mb4_unicode_ci,
  `place_info` json DEFAULT NULL,
  `cca2` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cca3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ccn3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capital` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `region` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subregion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `population` int DEFAULT NULL,
  `area` float DEFAULT NULL,
  `timezones` json DEFAULT NULL,
  `borders` json DEFAULT NULL,
  `flag_png` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `flag_svg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `languages` json DEFAULT NULL,
  `currency` json DEFAULT NULL,
  `demonyms` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  
/*/ 

/*/

Format of the JSON in the geo/regions folder

"properties":{"GID_1":"ARM.5_1","GID_0":"ARM","COUNTRY":"Armenia","NAME_1":"Gegharkunik","VARNAME_1":"Gelark'unik'","NL_NAME_1":"Գեղարքունիք|Гегаркуник","TYPE_1":"Marz","ENGTYPE_1":"Province","CC_1":"NA","HASC_1":"AM.GR","ISO_1":"NA"}}

/*/


/*/ JSON response format from GeoNames API

http://api.geonames.org/searchJSON?q=Kandahar&country=AF&maxRows=1&username=elpuma

{"totalResultsCount":4225,"geonames":[
{"adminCode1":"23","lng":"65.71013","geonameId":1138336,"toponymName":"Kandahār","countryId":"1149361","fcl":"P","population":523300,"countryCode":"AF","name":"Kandahar","fclName":"city, village,...","adminCodes1":{"ISO3166_2":"KAN"},"countryName":"Afghanistan","fcodeName":"seat of a first-order administrative division","adminName1":"Kandahar","lat":"31.61332","fcode":"PPLA"}]}
    
/*/

// Database connection
// --- Database Connection --- \\
require('connection.php');

// Path to the geo/regions folder
$regionsFolder = '../geo/regions';

// GeoNames API username
$geoNamesUsername = 'elpuma';

foreach (glob("$regionsFolder/*/*.json") as $file) {
    $regionData = file_get_contents($file);
    $regionInfo = json_decode($regionData, true);

    // Extract the name from the filename
    $name1 = pathinfo($file, PATHINFO_FILENAME);

    if ($regionInfo && isset($regionInfo['features'])) {
        foreach ($regionInfo['features'] as $feature) {
            $properties = $feature['properties'];

            $gid1 = $properties['GID_1'] ?? 'NULL';
            $country_real = $properties['COUNTRY'] ?? 'NULL';
            $country = $properties['CCA2'] ?? 'NULL'; // Use 2-letter country code

            // Escape variables used in SQL queries
            $country_real = $connection->real_escape_string($country_real);
            $name1 = $connection->real_escape_string($name1);

            // If CCA2 is NULL, query the database for the 2-letter code
            if ($country === 'NULL') {
                $name1 = $properties['NAME_1'] ?? 'NULL';
                $name1 = $connection->real_escape_string($name1);
                $query = "SELECT cca2, cca3, place_name_utf8, region, subregion FROM place_names WHERE place_name_ascii = '$country_real' LIMIT 1";
                $result = $connection->query($query);
                if ($result && $row = $result->fetch_assoc()) {
                    $country = $row['cca2'];
                    $country_utf8 = $row['place_name_utf8'];
                    $country_cca3 = $row['cca3'];
                    $country_region = $row['region'];
                    $country_subregion = $row['subregion'];
                } else {
                    echo "No country code found in database for: $name1\n";
                }
            }

            // Call GeoNames API to get additional data
            $geoNamesUrl = "http://api.geonames.org/searchJSON?q=$name1&country=$country&maxRows=1&username=$geoNamesUsername";
            echo "SUCCESS GeoNames API endpoint: $geoNamesUrl\n";
            
            $geoNamesResponse = file_get_contents($geoNamesUrl);
            //echo "GeoNames API response for $name1: $geoNamesResponse\n"; // Debugging
            $geoNamesData = json_decode($geoNamesResponse, true);

            if ($geoNamesData && isset($geoNamesData['geonames'][0])) {
                $geoInfo = $geoNamesData['geonames'][0];

                // Escape single quotes in the data
                $latitude = $connection->real_escape_string($geoInfo['lat'] ?? 'NULL');
                $longitude = $connection->real_escape_string($geoInfo['lng'] ?? 'NULL');
                $population = $connection->real_escape_string($geoInfo['population'] ?? 'NULL');
                $toponymName = $connection->real_escape_string($geoInfo['toponymName'] ?? $name1);
                $cca3 = $connection->real_escape_string($geoInfo['adminCodes1']['ISO3166_2'] ?? 'NULL');

                // Escape place names
                $country_utf8 = $connection->real_escape_string($country_utf8);
                $country_region = $connection->real_escape_string($country_region);
                $country_subregion = $connection->real_escape_string($country_subregion);

                $insertQuery = "
                    INSERT INTO place_names (
                        level, parent_utf8, parent_ascii, place_name_utf8, place_name_ascii, 
                        place_info, cca2, cca3, ccn3, capital, region, subregion, 
                        latitude, longitude, population, area, timezones, borders,
                        flag_png, flag_svg, languages, currency, demonyms
                    ) VALUES (
                        2, '$country_utf8', '$country_real', '$toponymName', '$name1', 
                        NULL, '$country', " . (strlen($cca3) > 3 ? "NULL" : "'$cca3'") . ", '$country_cca3', NULL, '$country_region', '$country_subregion', 
                        '$latitude', '$longitude', '$population', NULL, NULL, NULL,
                        NULL, NULL, NULL, NULL, NULL
                    )
                    ON DUPLICATE KEY UPDATE
                        level = VALUES(level),
                        parent_utf8 = VALUES(parent_utf8),
                        parent_ascii = VALUES(parent_ascii),
                        place_name_utf8 = VALUES(place_name_utf8),
                        place_name_ascii = VALUES(place_name_ascii),
                        place_info = VALUES(place_info),
                        cca2 = VALUES(cca2),
                        cca3 = VALUES(cca3),
                        ccn3 = VALUES(ccn3),
                        capital = VALUES(capital),
                        region = VALUES(region),
                        subregion = VALUES(subregion),
                        latitude = VALUES(latitude),
                        longitude = VALUES(longitude),
                        population = VALUES(population),
                        area = VALUES(area),
                        timezones = VALUES(timezones),
                        borders = VALUES(borders),
                        flag_png = VALUES(flag_png),
                        flag_svg = VALUES(flag_svg),
                        languages = VALUES(languages),
                        currency = VALUES(currency),
                        demonyms = VALUES(demonyms)
                ";

                echo "Insert Query: $insertQuery\n";

                // Execute the insert query
                if ($connection->query($insertQuery) === TRUE) {
                    echo "Successfully inserted/updated record for $name1\n";
                } else {
                    echo "Error inserting/updating record for $name1: " . $connection->error . "\n";
                }
            } else {
                echo "No GeoNames data found for: $name1\n";
                echo "GeoNames API endpoint: $geoNamesUrl\n";
                echo "Database query for country code: $query\n"; 
            }
        }
    }
}

$connection->close();