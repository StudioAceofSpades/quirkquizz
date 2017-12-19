#!/bin/bash

if [ "$#" -ne 2 ]; then
    echo "Usage is: ./db_setup [URL] [SITENAME]"
    echo "Example: ./db_setup http://example.com 'Example Site'"
    echo "A new file named new_db.sql will be created."
    exit
fi

if [ ! -f premise.sql ]; then
    echo "No file premise.sql!"
    exit
fi

SITEURL="$1"
SITENAME="$2"

echo "Updating URL..."
PREMISE_URL='http://premise\.local'
sed "s|$PREMISE_URL|$SITEURL|g" premise.sql > new_db.sql
echo "Done!"

echo "Updating site name..."
sed -i "s/Premise Local/$SITENAME/g" new_db.sql
echo "Done!"

