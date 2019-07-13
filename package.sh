#!/bin/bash
set -e

echo -n "Release version: "
read version

dirname="ics-display-${version}"
mkdir "$dirname"
cp -tR $dirname \
  src/bg.jpg \
  src/calendar.js \
  src/config.example.php \
  src/index.html \
  src/loader.gif \
  src/schedule.php \
  src/style.css \
  src/vendor \
  LICENSE \
  changelog.txt

if [ ! -d releases ]; then
  mkdir releases
fi

zip -r "releases/${dirname}.zip" "$dirname"
rm -rf "$dirname"
