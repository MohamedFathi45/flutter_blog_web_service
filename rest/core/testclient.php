<?php
$url = 'http://localhost/flutter_blog_web_service/rest/core/user.php/';
$data = <<<XML
<users>
<user>
    <username>mohamedFathi</username>
    <password>the password</password>
    <email>sharksmardo@gmail.com</email>
</user>
</users>
XML;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>