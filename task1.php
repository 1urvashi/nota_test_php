<?php

function findFilesWithPattern($directory, $pattern, $extension) {
    $matchingFiles = [];

    // Check if the directory exists
    if (is_dir($directory)) {
        // Get a list of all files in the directory
        $files = scandir($directory);

        // Iterate through each file and match the pattern
        foreach ($files as $file) {
            if (preg_match($pattern, $file) && pathinfo($file, PATHINFO_EXTENSION) == $extension) {
                $matchingFiles[] = $file;
            }
        }

        // Sort the matching files alphabetically
        sort($matchingFiles);
    }

    return $matchingFiles;
}

// Directory to search for files
$directory = "/datafiles";

// Regular expression pattern to match file names
$pattern = '/^[a-zA-Z0-9]+\.ixt$/';

// File extension to match
$extension = ".ixt";

// Find and display matching files
$matchingFiles = findFilesWithPattern($directory, $pattern, $extension);

// Output the matching file names
if (count($matchingFiles) > 0) {
    echo "Matching Files:\n";
    foreach ($matchingFiles as $file) {
        echo $file . "\n";
    }
} else {
    echo "No matching files found in the directory.\n";
}
?>
