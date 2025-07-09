<?php
// function copied from answer on Stack Overflow
// https://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
// I took the first answer and removed the call to htmlspecialchars() as it's not needed
// (SimpleXMLElement::addChild translates xml special characters to their char entities automatically)
//
function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key","$value");
        }
     }
}
?>
