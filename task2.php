<?php
// Create a connection to your database.
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "nota_test";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// URL of the Wikipedia page to download
$url = "https://www.wikipedia.org/";

// Download the webpage
$html = file_get_contents($url);

// Parse the HTML using DOMDocument
$doc = new DOMDocument();
libxml_use_internal_errors(true); 
$doc->loadHTML($html);

// Find and process the sections of the page
$sections = $doc->getElementsByTagName('section');
foreach ($sections as $section) {

    // Extract and sanitize the title
    $title = $section->getElementsByTagName('h2')->item(0)->textContent;
    $title = substr($title, 0, 230); // Truncate to a maximum of 230 characters
    $title = htmlspecialchars($title, ENT_QUOTES);

    // Extract and sanitize the abstract
    $abstract = $section->getElementsByTagName('p')->item(0)->textContent;
    $abstract = substr($abstract, 0, 256); // Truncate to a maximum of 256 characters
    $abstract = htmlspecialchars($abstract, ENT_QUOTES);

    // Extract and sanitize the picture URL
    $picture = $section->getElementsByTagName('img')->item(0)->getAttribute('src');
    $picture = substr($picture, 0, 240); // Truncate to a maximum of 240 characters
    $picture = htmlspecialchars($picture, ENT_QUOTES);

    // Extract the link
    $link = $section->getElementsByTagName('a')->item(0)->getAttribute('href');

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) 
                            VALUES (NOW(), :title, :url, :picture, :abstract)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':picture', $picture);
    $stmt->bindParam(':abstract', $abstract);
    $stmt->execute();
}

// Close the database connection
$conn = null;

?>