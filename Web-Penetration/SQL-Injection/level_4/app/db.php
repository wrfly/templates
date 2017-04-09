<?php

$lnk = mysqli_connect("", "root", "w", "", 0, "/run/mysqld.sock");
if (!$lnk) {
	die(mysqli_connect_error());
}

if (!file_exists('sql_imported.lock')) {
	// Name of the file
	$filename = 'data.sql';
	// Temporary variable, used to store current query
	$templine = '';
	// Read in entire file
	$lines = file($filename);
	// Loop through each line
	foreach ($lines as $line) {
		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '') {
			continue;
		}

		// Add this line to the current segment
		$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
		if (substr(trim($line), -1, 1) == ';') {
			// Perform the query
			mysqli_query($lnk, $templine);
			// Reset temp variable to empty
			$templine = '';
		}
	}
	fopen("sql_imported.lock", "w");
	unlink($filename);
}

$lnk = mysqli_connect("", "root", "w", "web_penetration", 0, "/run/mysqld.sock");

?>