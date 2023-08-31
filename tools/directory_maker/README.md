Here is a README.md file for the country/state directory structure script:

# Country State Directory Script

This script generates a folder structure of countries containing state/province subfolders based on ISO 3166-1 and ISO 3166-2 codes.

This script is not perfect, and will create directories which may be more than you need, and sometimes the codes may not make sense. Please understand that some nations do not have a letter-based representation of their administrative districts. 

## Usage

To use the script:

1. Clone this repo
2. Make the script executable: `chmod +x countries.sh`
3. Run the script: `./countries.sh`

This will create the country/state directory structure in the current working directory.

## What It Does

The script does the following:

- Gets a list of ISO 3166-1 alpha-3 country codes for UN member and observer states
- For each country:
  - Looks up the ISO 3166-2 country code 
  - Creates a folder using the ISO 3166-1 alpha-3 code 
  - Downloads a CSV of states mapped to countries
  - Filters the CSV to only get states for that country
  - Creates subfolders for each state using the ISO 3166-2 code

This results in a folder structure like:

```
AUS/
    NSW/
    QLD/
    VIC/
    ...
USA/
    CA/
    TX/
    NY/
    ...
```

Requirements
The script requires:

bash
curl
jq
To install the dependencies:

Linux
bash

Copy code

# Install curl
sudo apt install curl 

# Install jq 
sudo apt install jq
macOS
bash

Copy code

# Install curl 
brew install curl

# Install jq
brew install jq
You can verify the dependencies are installed by running:

bash

Copy code

curl --version
jq --version
If you get a command not found error, install the missing dependency for your system.

## Data Sources

- Country codes - Hardcoded list of UN member and observer states 
- State codes - [Country-State-City CSV dataset](https://github.com/dr5hn/countries-states-cities-database)
- Country lookup - [REST Countries API](https://restcountries.com/)

## License

This script is open source and available under the MIT License.

Let me know if you would like me to explain or expand any part of the README!