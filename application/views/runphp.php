<?php
// Command to execute the JavaScript code using Node.js
$command = "node httpdocs/JavaScript_Scraping/fetch_url.js";

// Execute the command and capture the output
$output = shell_exec($command);

// Output the result
echo "<pre>$output</pre>";
?>