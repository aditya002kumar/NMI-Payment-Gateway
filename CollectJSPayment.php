<?php

try {
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $amount = $_POST['amount'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $zip = $_POST['zip'];
  $country = $_POST['country'];
  $payment_token = $_POST['payment_token'];
  
  $url = 'https://secure.networkmerchants.com/api/transact.php';
  $vars = 'security_key=hbq5e8yEd6637v7z5KKWJDVZgbVvv82e'
  . "&type=sale"
  . "&payment=creditcard"
  . "&first_name=". $fname
  . "&last_name=". $lname
  . "&phone=". $phone
  . "&email=". $email
  . "&amount=". $amount
  . "&address1=". $address
  . "&city=". $city
  . "&state=". $state
  . "&postal=". $zip
  . "&country=". $country
  . "&payment_token=". $payment_token;
  
  $ch = curl_init( $url );
  curl_setopt( $ch, CURLOPT_POST, 1);
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars);
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt( $ch, CURLOPT_HEADER, 1);
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec($ch);

  if (strpos($response, 'HTTP/1.1 200 OK') === 0) {
      preg_match('/responsetext=([^&]+)/', $response, $responseTextMatches);
      preg_match('/transactionid=([^&]+)/', $response, $transactionIdMatches);
      preg_match('/authcode=([^&]+)/', $response, $authCodeMatches);

      $responseText = isset($responseTextMatches[1]) ? $responseTextMatches[1] : null;
      $transactionId = isset($transactionIdMatches[1]) ? $transactionIdMatches[1] : null;
      $authCode = isset($authCodeMatches[1]) ? $authCodeMatches[1] : null;

      echo "Response Text: $responseText\n";
      echo "Transaction ID: $transactionId\n";
      echo "Auth Code: $authCode\n";

      // Encoding the variables as query parameters
      $responseTextEncoded = urlencode($responseText);
      $transactionIdEncoded = urlencode($transactionId);
      $authCodeEncoded = urlencode($authCode);
      
      // Redirecting with the query parameters
      $redirectURL = "CollectJSSuccess.html?responseText=$responseTextEncoded&transactionId=$transactionIdEncoded&authCode=$authCodeEncoded";
      header("Location: $redirectURL");
      exit();

  }
  else {
      echo "HTTP response is not 200";
  }

  curl_close($ch);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>