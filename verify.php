<?php
$access_token = '0HSB7lPEM4zUCOYk9qQVNRJJndXzEeVW59UdLPzKN+NVPUAvbA9RCHCR3+pR57bHMZM1gsneTm5Dzd6iLH9fCJJlKRjD6T5Y0ierWK0e9vdgJ6tvqUMAA9tC6PsbsTYtzcpcYEHjTo+RWLMg0HrLHgdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;