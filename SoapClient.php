<?php header('Content-Type: text/html; charset=utf-8');
include_once("../../db/dbConnection.php");

function Servis($url,$session,$body)
{

    $soapUrl = $url;
    $xml_post_string = $body;
    $headers = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Host: home.example.com",
        "Cookie: " . $session,
        "Content-length: " . strlen($xml_post_string),
    );

    $url = $soapUrl;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $body = curl_exec($ch);
    curl_close($ch);

    $body1 = str_replace("<soap:Body>", "", $body);
    $body2 = str_replace("</soap:Body>", "", $body1);

    $parser = simplexml_load_string($body2);


    $path = $parser // $parser->$parserChild->...;
    $pth = json_decode(json_encode($path), true);

    //print_r($parser);
    return $pth;
}

$body = file_get_contents('http://home.example.com/WebServices/Session.asmx/Login2?Username=********&Password=*****');
$parser = json_decode(json_encode((array) simplexml_load_string($body)), true);

if($parser[0] == "true"){
    $headparser = explode(" ",$http_response_header[3]);
    $session = rtrim($headparser[1],";");
}else{
    exit("Oturum Açılamadı.");
}

$urunlerUrl = "http://home.example.com/WebServices/example.asmx?op=***";

    $urunlerBody = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                        <soap:Body>

                        </soap:Body>
                    </soap:Envelope>';

Servis($urunlerUrl,$session,$urunlerBody);
?>