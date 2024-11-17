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


/*/ JSON response format


https://restcountries.com/v3.1/alpha/FRA 
[{"name":{"common":"France","official":"French Republic","nativeName":{"fra":{"official":"République française","common":"France"}}},"tld":[".fr"],"cca2":"FR","ccn3":"250","cca3":"FRA","cioc":"FRA","independent":true,"status":"officially-assigned","unMember":true,"currencies":{"EUR":{"name":"Euro","symbol":"€"}},"idd":{"root":"+3","suffixes":["3"]},"capital":["Paris"],"altSpellings":["FR","French Republic","République française"],"region":"Europe","subregion":"Western Europe","languages":{"fra":"French"},"translations":{"ara":{"official":"الجمهورية الفرنسية","common":"فرنسا"},"bre":{"official":"Republik Frañs","common":"Frañs"},"ces":{"official":"Francouzská republika","common":"Francie"},"cym":{"official":"French Republic","common":"France"},"deu":{"official":"Französische Republik","common":"Frankreich"},"est":{"official":"Prantsuse Vabariik","common":"Prantsusmaa"},"fin":{"official":"Ranskan tasavalta","common":"Ranska"},"fra":{"official":"République française","common":"France"},"hrv":{"official":"Francuska Republika","common":"Francuska"},"hun":{"official":"Francia Köztársaság","common":"Franciaország"},"ita":{"official":"Repubblica francese","common":"Francia"},"jpn":{"official":"フランス共和国","common":"フランス"},"kor":{"official":"프랑스 공화국","common":"프랑스"},"nld":{"official":"Franse Republiek","common":"Frankrijk"},"per":{"official":"جمهوری فرانسه","common":"فرانسه"},"pol":{"official":"Republika Francuska","common":"Francja"},"por":{"official":"República Francesa","common":"França"},"rus":{"official":"Французская Республика","common":"Франция"},"slk":{"official":"Francúzska republika","common":"Francúzsko"},"spa":{"official":"República francés","common":"Francia"},"srp":{"official":"Француска Република","common":"Француска"},"swe":{"official":"Republiken Frankrike","common":"Frankrike"},"tur":{"official":"Fransa Cumhuriyeti","common":"Fransa"},"urd":{"official":"جمہوریہ فرانس","common":"فرانس"},"zho":{"official":"法兰西共和国","common":"法国"}},"latlng":[46.0,2.0],"landlocked":false,"borders":["AND","BEL","DEU","ITA","LUX","MCO","ESP","CHE"],"area":551695.0,"demonyms":{"eng":{"f":"French","m":"French"},"fra":{"f":"Française","m":"Français"}},"flag":"\uD83C\uDDEB\uD83C\uDDF7","maps":{"googleMaps":"https://goo.gl/maps/g7QxxSFsWyTPKuzd7","openStreetMaps":"https://www.openstreetmap.org/relation/1403916"},"population":67391582,"gini":{"2018":32.4},"fifa":"FRA","car":{"signs":["F"],"side":"right"},"timezones":["UTC-10:00","UTC-09:30","UTC-09:00","UTC-08:00","UTC-04:00","UTC-03:00","UTC+01:00","UTC+02:00","UTC+03:00","UTC+04:00","UTC+05:00","UTC+10:00","UTC+11:00","UTC+12:00"],"continents":["Europe"],"flags":{"png":"https://flagcdn.com/w320/fr.png","svg":"https://flagcdn.com/fr.svg","alt":"The flag of France is composed of three equal vertical bands of blue, white and red."},"coatOfArms":{"png":"https://mainfacts.com/media/images/coats_of_arms/fr.png","svg":"https://mainfacts.com/media/images/coats_of_arms/fr.svg"},"startOfWeek":"monday","capitalInfo":{"latlng":[48.87,2.33]},"postalCode":{"format":"#####","regex":"^(\\d{5})$"}}]

    https://restcountries.com/v3.1/alpha/GUA
[{"name":{"common":"Guatemala","official":"Republic of Guatemala","nativeName":{"spa":{"official":"República de Guatemala","common":"Guatemala"}}},"tld":[".gt"],"cca2":"GT","ccn3":"320","cca3":"GTM","cioc":"GUA","independent":true,"status":"officially-assigned","unMember":true,"currencies":{"GTQ":{"name":"Guatemalan quetzal","symbol":"Q"}},"idd":{"root":"+5","suffixes":["02"]},"capital":["Guatemala City"],"altSpellings":["GT"],"region":"Americas","subregion":"Central America","languages":{"spa":"Spanish"},"translations":{"ara":{"official":"جمهورية غواتيمالا","common":"غواتيمالا"},"bre":{"official":"Republik Guatemala","common":"Guatemala"},"ces":{"official":"Republika Guatemala","common":"Guatemala"},"cym":{"official":"Republic of Guatemala","common":"Guatemala"},"deu":{"official":"Republik Guatemala","common":"Guatemala"},"est":{"official":"Guatemala Vabariik","common":"Guatemala"},"fin":{"official":"Guatemalan tasavalta","common":"Guatemala"},"fra":{"official":"République du Guatemala","common":"Guatemala"},"hrv":{"official":"Republika Gvatemala","common":"Gvatemala"},"hun":{"official":"Guatemalai Köztársaság","common":"Guatemala"},"ita":{"official":"Repubblica del Guatemala","common":"Guatemala"},"jpn":{"official":"グアテマラ共和国","common":"グアテマラ"},"kor":{"official":"과테말라 공화국","common":"과테말라"},"nld":{"official":"Republiek Guatemala","common":"Guatemala"},"per":{"official":"جمهوری گواتِمالا","common":"گواتِمالا"},"pol":{"official":"Republika Gwatemali","common":"Gwatemala"},"por":{"official":"República da Guatemala","common":"Guatemala"},"rus":{"official":"Республика Гватемала","common":"Гватемала"},"slk":{"official":"Guatemalská republika","common":"Guatemala"},"spa":{"official":"República de Guatemala","common":"Guatemala"},"srp":{"official":"Република Гватемала","common":"Гватемала"},"swe":{"official":"Republiken Guatemala","common":"Guatemala"},"tur":{"official":"Guatemala Cumhuriyeti","common":"Guatemala"},"urd":{"official":"جمہوریہ گواتیمالا","common":"گواتیمالا"},"zho":{"official":"危地马拉共和国","common":"危地马拉"}},"latlng":[15.5,-90.25],"landlocked":false,"borders":["BLZ","SLV","HND","MEX"],"area":108889.0,"demonyms":{"eng":{"f":"Guatemalan","m":"Guatemalan"},"fra":{"f":"Guatémaltèque","m":"Guatémaltèque"}},"flag":"\uD83C\uDDEC\uD83C\uDDF9","maps":{"googleMaps":"https://goo.gl/maps/JoRAbem4Hxb9FYbVA","openStreetMaps":"https://www.openstreetmap.org/relation/1521463"},"population":16858333,"gini":{"2014":48.3},"fifa":"GUA","car":{"signs":["GCA"],"side":"right"},"timezones":["UTC-06:00"],"continents":["North America"],"flags":{"png":"https://flagcdn.com/w320/gt.png","svg":"https://flagcdn.com/gt.svg","alt":"The flag of Guatemala is composed of three equal vertical bands of light blue, white and light blue, with the national coat of arms centered in the white band."},"coatOfArms":{"png":"https://mainfacts.com/media/images/coats_of_arms/gt.png","svg":"https://mainfacts.com/media/images/coats_of_arms/gt.svg"},"startOfWeek":"monday","capitalInfo":{"latlng":[14.62,-90.52]},"postalCode":{"format":"#####","regex":"^(\\d{5})$"}}]

    
/*/




// Path to the geo/countries folder
$geoFolder = '../geo/countries';
// ... existing database connection ...




// ... existing code ...

foreach (glob("$geoFolder/*.json") as $file) {
    $iso3 = pathinfo($file, PATHINFO_FILENAME);
    echo "Processing file: $file\n"; // Echo the file being processed

    // Uncomment these lines when ready to fetch real data
    $apiUrl = "https://restcountries.com/v3.1/alpha/$iso3";
    $countryData = file_get_contents($apiUrl);
    $countryInfo = json_decode($countryData, true);

    if ($countryInfo && isset($countryInfo[0]['name'])) {
        echo "Country data found for: $iso3\n"; // Echo when country data is found

        // Basic place name information
        $placeNameUtf8 = null;
        if (isset($countryInfo[0]['languages']) && is_array($countryInfo[0]['languages'])) {
            $languageKey = array_key_first($countryInfo[0]['languages']);
            if (isset($countryInfo[0]['name']['nativeName'][$languageKey]['common'])) {
                $placeNameUtf8 = $countryInfo[0]['name']['nativeName'][$languageKey]['common'];
            }
        }
        $placeNameAscii = $countryInfo[0]['name']['common'];
        echo "Place Name (UTF-8): $placeNameUtf8, Place Name (ASCII): $placeNameAscii\n"; // Echo place names

        // Extract additional fields
        $cca2 = $countryInfo[0]['cca2'];
        $cca3 = $countryInfo[0]['cca3'];
        $ccn3 = $countryInfo[0]['ccn3'];
        $capital = isset($countryInfo[0]['capital'][0]) ? $countryInfo[0]['capital'][0] : null;
        $region = $countryInfo[0]['region'];
        $subregion = isset($countryInfo[0]['subregion']) ? $countryInfo[0]['subregion'] : null;
        $latitude = $countryInfo[0]['latlng'][0];
        $longitude = $countryInfo[0]['latlng'][1];
        $population = $countryInfo[0]['population'];
        $area = $countryInfo[0]['area'];
        $timezones = json_encode($countryInfo[0]['timezones']);
        $borders = isset($countryInfo[0]['borders']) ? json_encode($countryInfo[0]['borders']) : null;
        $flagPng = $countryInfo[0]['flags']['png'];
        $flagSvg = $countryInfo[0]['flags']['svg'];
        $languages = json_encode($countryInfo[0]['languages']);
        $currencies = isset($countryInfo[0]['currencies']) ? json_encode($countryInfo[0]['currencies']) : null;
        $demonyms = json_encode($countryInfo[0]['demonyms']);

        // Prepare the insert or update query
        $stmt = $connection->prepare("
            INSERT INTO place_names (
                level, parent_utf8, parent_ascii, place_name_utf8, place_name_ascii, 
                place_info, cca2, cca3, ccn3, capital, region, subregion, 
                latitude, longitude, population, area, timezones, borders,
                flag_png, flag_svg, languages, currency, demonyms
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
            ON DUPLICATE KEY UPDATE
                level = VALUES(level),
                parent_utf8 = VALUES(parent_utf8),
                parent_ascii = VALUES(parent_ascii),
                place_name_utf8 = VALUES(place_name_utf8),
                place_name_ascii = VALUES(place_name_ascii),
                place_info = VALUES(place_info),
                cca2 = VALUES(cca2),
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
        ");

        $level = 1;
        $parentUtf8 = 'gaia';
        $parentAscii = 'gaia';
        $placeInfo = json_encode($countryInfo[0]); // Store complete response

        // Bind all parameters
        $stmt->bind_param(
            "isssssssssssddidsssssss",
            $level, $parentUtf8, $parentAscii, $placeNameUtf8, $placeNameAscii,
            $placeInfo, $cca2, $cca3, $ccn3, $capital, $region, $subregion,
            $latitude, $longitude, $population, $area, $timezones, $borders,
            $flagPng, $flagSvg, $languages, $currencies, $demonyms
        );

        // Debug output
        echo "Prepared statement for: $placeNameAscii ($cca3)\n";

        // Execute the statement
        if (!$stmt->execute()) {
            echo "Error inserting/updating record for $cca3: " . $stmt->error . "\n";
        } else {
            echo "Successfully inserted/updated record for $placeNameAscii ($cca3)\n";
        }
    } else {
        echo "No data found for: $iso3\n"; // Echo when no data is found
    }
}

$connection->close();