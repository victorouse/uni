<?php
    function example_query($condition) {
        // Access to database 
        global $db

        // Extra example parameter to pass
        $and_condtion = "this too";

        // Construct query
        $query = 
            "SELECT * FROM ExampleTable
            WHERE Attribute1 = ?
            AND Attribute2 = ?";

        $stmt = $db->prepare($query);

        // Pass parameters in order through an array,
        // even if there is only one parameter  
        $stmt->execute(array($condition, $and_condition));

        // Get result and put into associative array
        // e.g access it with $result['attribute']
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return array,
        // or return a status code (success/fail) 
        // if you're doing an INSERT/DELETE/UPDATE
        return $result;
    }
?>
