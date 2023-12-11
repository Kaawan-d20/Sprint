<?php 
$date = "2023-12-4"; // Date quelconque
$timestamp = strtotime($date);
$lundi = date('Y-m-d', strtotime('sunday this week', $timestamp));
echo $lundi;


$date = new DateTime('2023-11-27 00:00:00');
$dateFormatee = $date->format('d-m-Y H:i:s');

echo $dateFormatee; // Affiche '27-11-2023 00:00:00'


