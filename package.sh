#!/bin/bash
set -e

echo -n "Release version: "
read version

dirname="ics-display-${version}"
mkdir "$dirname"
cp src/bg.jpg "$dirname"
cp src/calendar.js "$dirname"
cp src/config.example.php "$dirname"
cp src/index.html "$dirname"
cp src/loader.gif "$dirname"
cp src/schedule.php "$dirname"
cp src/style.css "$dirname"
cp LICENSE "$dirname"
cp changelog.txt "$dirname"
cp README.md "$dirname"
cp screenshot.png "$dirname"
cp -r src/vendor "$dirname"

if [ ! -d releases ]; then
  mkdir releases
fi

zip -r "releases/${dirname}.zip" "$dirname"
rm -rf "$dirname"
