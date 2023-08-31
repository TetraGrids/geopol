#!/bin/bash

# List of ISO 3166-1 alpha-3 codes for UN member and observer states
isos=("AFG" "ALB" "DZA" "AND" "AGO" "ATG" "ARG" "ARM" "AUS" "AUT" "AZE" "BHS" "BHR" "BGD" "BRB" "BLR" "BEL" "BLZ" "BEN" "BTN" "BOL" "BIH" "BWA" "BRA" "BRN" "BGR" "BFA" "MMR" "BDI" "CPV" "KHM" "CMR" "CAN" "CAF" "TCD" "CHL" "CHN" "COL" "COM" "COG" "COD" "COK" "CRI" "CIV" "HRV" "CUB" "CYP" "CZE" "PRK" "COD" "DNK" "DJI" "DMA" "DOM" "ECU" "EGY" "SLV" "GNQ" "ERI" "EST" "SWZ" "ETH" "FJI" "FIN" "FRA" "GAB" "GMB" "GEO" "DEU" "GHA" "GRC" "GRD" "GTM" "GIN" "GNB" "GUY" "HTI" "HND" "HUN" "ISL" "IND" "IDN" "IRN" "IRQ" "IRL" "ISR" "ITA" "JAM" "JPN" "JOR" "KAZ" "KEN" "KIR" "PRK" "KOR" "KWT" "KGZ" "LAO" "LVA" "LBN" "LSO" "LBR" "LBY" "LIE" "LTU" "LUX" "MAC" "MDG" "MWI" "MYS" "MDV" "MLI" "MLT" "MHL" "MRT" "MUS" "MEX" "FSM" "MDA" "MCO" "MNG" "MNE" "MAR" "MOZ" "NAM" "NPL" "NLD" "NZL" "NIC" "NER" "NGA" "MKD" "NOR" "OMN" "PAK" "PLW" "PSE" "PAN" "PNG" "PRY" "PER" "PHL" "POL" "PRT" "QAT" "ROU" "RUS" "RWA" "LCA" "VCT" "WSM" "SMR" "STP" "SAU" "SEN" "SRB" "SYC" "SLE" "SGP" "SVK" "SVN" "SLB" "SOM" "ZAF" "SSD" "ESP" "LKA" "SDN" "SUR" "SWE" "CHE" "SYR" "TJK" "THA" "TLS" "TGO" "TON" "TTO" "TUN" "TUR" "TKM" "TUV" "UGA" "UKR" "ARE" "GBR" "USA" "URY" "UZB" "VUT" "VEN" "VNM" "YEM" "ZMB" "ZWE")

# GitHub CSV URL
states_url="https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/csv/states.csv" 

for iso in "${isos[@]}" 
do
  # Get 2 letter country code
  country=$(curl -s "https://restcountries.com/v3.1/alpha/$iso" | jq -r '.[].cca2')
  
  # Make country folder
  mkdir "$iso"
  cd "$iso"  

  # Get states for this country
  states=$(curl -s $states_url | grep ",$country," | cut -d',' -f6)

  # Loop through states
  for state in $states
  do  
    mkdir "$state"
  done

  cd ..
done