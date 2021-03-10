# Test Results and Learning Progress Export

Export plugin for Test Results incl. Learning Progress. Compatible with ILIAS 5.3.0 - 5.3.999.

### Usage ###
Install the plugin ...

... using git:
```bash
mkdir -p Customizing/global/plugins/Modules/Test/Export
cd Customizing/global/plugins/Modules/Test/Export
git clone https://github.com/DatabayAG/ExcelMatrixResults.git -b release_1-2
```

... from zip file:
```bash
mkdir -p Customizing/global/plugins/Modules/Test/Export
cd Customizing/global/plugins/Modules/Test/Export
wget https://github.com/DatabayAG/archive/release_1-2.zip
unzip master.zip
rm -f master.zip
mv ExcelMatrixResults-master ExcelMatrixResults
```

Go to the ILIAS plugin administration and activate the plugin.

You now have an additional export option for tests.

### Credits ###
Development for ILIAS by Björn Heyser, Maintainer Test and Assessment.

