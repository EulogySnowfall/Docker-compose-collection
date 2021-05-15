<?php

function getSecretKey($username, $password, $server){

    $request = [];
    $url = "https://" . $server . ":8443/api/v2/auth/keys";
    $ch = curl_init( $url );
    $payload = json_encode($request);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "Accept:application/json")) ;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    
    # Send request.
    $result = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    # Print response.
    if ($curl_errno > 0) {
        echo "cURL Error ($curl_errno): $curl_error <br/>";
        return "";
    } else {
        $data = json_decode($result);
        return $data->key;
    }

}

function getLinkUrl($account, $secretKey, $server){

    $url = "https://" . $server . ":8443/api/v2/cli/admin/call";
    $request = array("params" => array("--get-login-link", "-user", $account));
    $key = "X-API-Key: $secretKey";

    $ch = curl_init( $url );
    $payload = json_encode($request);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($key, "Content-Type:application/json", "Accept:application/json")) ;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

    # Send request.
    $result = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    # Print response.
    if ($curl_errno > 0) {
        echo "cURL Error ($curl_errno): $curl_error <br/>";
        return "";
    } else {
        $data = json_decode($result);
        if($data->code == 0){
            return $data->stdout;
        }else{
            echo "Curl Error code: $data->code <br/>";
            echo "Error message: $data->stderr <br/>";
            return "";
        }
    }
    
}

    if(isset($_POST['submit'])){
        $server = "host.com";
        $username = 'admin';
        $password = 'passX';

        # $secretKey = getSecretKey($username,$password,$server);
        # echo "New Key : $secretKey <br/>";

        $secretKey = "1a2d23f32-d2d243-d22-d22-d2222w";
        
        $account = "sitecom";

        $result = getLinkUrl($account, $secretKey, $server);
        #$result = getLinkUrlWithUser($account,$username,$password,$server);
        #echo "Résultat : $result";

        // cookie
        #setcookie('auth_link', $result, time() + 84600);

        // Send to proxy
        header("location: $result");

    }


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHPSELF'] ?>" method="POST">
    <input type="submit" name="submit" value="submit">
</form>

</body>
</html>
