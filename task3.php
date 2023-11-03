<?php

/**
 * Class TableCreator
 *
 * This class is used to create a table called 'Test' and perform operations on it.
 *
 * @final
 */
final class TableCreator {
    private $db; // Database connection object

    public function __construct() {
        
        $this->db = new PDO("mysql:host=localhost;dbname=nota_test", "root", "root");

        // Create the 'Test' table
        $this->create();
        // Fill the 'Test' table with random data
        $this->fill();
    }

    /**
     * Create the 'Test' table with specified fields.
     *
     * This method is accessible only within the class.
     */
    private function create() {
        $query = "CREATE TABLE Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";
        
        $this->db->exec($query);
    }

    /**
     * Fill the 'Test' table with random data.
     *
     * This method is accessible only within the class.
     */
    private function fill() {
        
        $stmt = $this->db->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < 10; $i++) {
            $scriptName = "Script " . rand(1, 100);
            $startTime = date("Y-m-d H:i:s", rand(1609459200, 1709459200)); // Random date between two timestamps
            $endTime = date("Y-m-d H:i:s", rand(1709459200, 1809459200)); // Random date between two timestamps
            $result = ['normal', 'illegal', 'failed', 'success'][rand(0, 3)];

            $stmt->execute([$scriptName, $startTime, $endTime, $result]);
        }
    }

    /**
     * Select data from the 'Test' table based on the 'result' column.
     *
     * This method is accessible from outside the class.
     *
     * @return array
     */
    public function get() {
        $query = "SELECT * FROM Test WHERE result IN ('normal', 'success')";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Example usage:
$tableCreator = new TableCreator();
$data = $tableCreator->get();
print_r($data);

?>
