<?php
    function db(): mysqli
    {
        static $conn = null;
        if ($conn) return $conn;
  
        $conn = new mysqli('127.0.0.1', 'root', '', 'santri_belajar');
  
        if ($conn->connect_error) {
            die('Database error: ' . $conn->connect_error);
        }
  
        $conn->set_charset('utf8mb4');
        return $conn;
    }

?>