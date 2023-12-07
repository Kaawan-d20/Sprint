<?php 
$date = "2023-12-4"; // Date quelconque
$timestamp = strtotime($date);
$lundi = date('Y-m-d', strtotime('sunday this week', $timestamp));
echo $lundi;