# Test Results and Learning Progress Export

Export plugin for Test Results incl. Learning Progress.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL"
in this document are to be interpreted as described in
[RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

**Table of Contents**

* [Requirements](#requirements)
* [Installation](#installation)
    * [Composer](#composer)
* [Other information](#other-information)
    * [License](#license)

## Requirements

* PHP: [![Minimum PHP Version](https://img.shields.io/badge/Minimum_PHP-7.2.x-blue.svg)](https://php.net/) [![Maximum PHP Version](https://img.shields.io/badge/Maximum_PHP-7.4.x-blue.svg)](https://php.net/)
* ILIAS: [![Minimum ILIAS Version](https://img.shields.io/badge/Minimum_ILIAS-6.x-orange.svg)](https://ilias.de/) [![Maximum ILIAS Version](https://img.shields.io/badge/Maximum_ILIAS-7.x-orange.svg)](https://ilias.de/)

## Installation

... using Git:
```bash
mkdir -p Customizing/global/plugins/Modules/Test/Export
cd Customizing/global/plugins/Modules/Test/Export
git clone https://github.com/DatabayAG/ExcelMatrixResults.git
```

... from zip file:
```bash
mkdir -p Customizing/global/plugins/Modules/Test/Export
cd Customizing/global/plugins/Modules/Test/Export
wget https://github.com/DatabayAG/archive/master.zip
unzip master.zip
rm -f master.zip
mv ExcelMatrixResults-master ExcelMatrixResults
```

## Other information

#### License

See [LICENSE](./LICENSE) file in this repository.
