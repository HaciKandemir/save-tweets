<?php 

$variables = [
      'CONSUMER_KEY' => '******',
      'CONSUMER_SECRET' => '************',
      'OAUTH_CALLBACK' => '',
      'USER_NAME' => '******',
  ];

  foreach ($variables as $key => $value) {
      putenv("$key=$value");
  }

 ?>