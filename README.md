Shoreline
=========

How much shoreline does Minnesota have? Is it really more than California?

To reproduce my results, follow these steps:

# Create a MySQL database containing the tables listed in shoreline.sql
# Set your MySQL connection parameters at the top of shoreline.php
# Download the Minnesota, California, Hawaii, and Florida OSM files from Geofabrik. Get the `[state]-latest.osm.bz2` file for each state. http://download.geofabrik.de/north-america.html
# Un-bzip the OSM files.
# Filter the files to retrieve just the lakes: `osmfilter [state]-latest.osm --keep="natural=water" --drop-author --drop-version -o=[state].osm`
# Run `php shoreline.php [state].osm [state]_`  for each state.
# Re-running the scripts should be faster than the initial run, and it should give you the same results.