<?php

function getadres($postcode, $huisnr) {
    $url = 'http://geodata.nationaalgeoregister.nl/locatieserver/free?fq='.urlencode('postcode:'.$postcode).'&fq='.urlencode('huisnummer~'.$huisnr . '*');
    $content = file_get_contents( $url );
    $content_copy = $content;

    if ($content_copy == null)
        return null;
        
    $result = json_decode( $content_copy );
    
    // nothing found?
    if ((count($result->response->docs) == 0) or ($result == false))
        return null;
        
    $doc = $result->response->docs[0];
    
    $output = array(
        'provincie' => $doc->provincienaam,
        'stad' => $doc->woonplaatsnaam,
        'straat' => $doc->straatnaam
    );

    return $output;
}

echo "
<form method='post' action=" .$_SERVER['PHP_SELF'].">
    <label for='lbl_postcode'>Postcode</label><br>
    <input type='text' name='postcode'><br>
    <label for='lbl_huisnr'>Huis nummer</label><br>
    <input type='text' name='huisnr'><br>
    <button type='submit' value='Submit'>Opzoeken</button>
</form>";

if (isset($_POST['postcode'])) {
    $tmp = getadres($_POST['postcode'],$_POST['huisnr']);
    if ($tmp) {
        echo "
        Straat: ".$tmp['straat']." ".$_POST['huisnr']."<br>
        Postcode: ".$_POST['postcode']."<br>
        Stad: ".$tmp['stad']."<br>
        Provincie: ".$tmp['provincie']."<br>
        ";
    } else {
        echo "Niks gevonden.";
    }
}

?>
