<?php 
$date = "2023-12-4"; // Date quelconque
$timestamp = strtotime($date);

if (date('N', $timestamp)==2){
    echo $date;
}
else{
    $lundi = date('Y-m-d', strtotime('next sunday', $timestamp));
    echo $lundi;
}