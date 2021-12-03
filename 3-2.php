<?php
$diagnosticsList = file("diagnostics.list");

function runDiagnostics($diagnostics, $bitPosition) {
    $list1 = array();
    $list0 = array();

    //run through each binary value    
    foreach($diagnostics as $diagnostic) {
        //101010101010
        //split string into array of ints
        $diagnosticBits = str_split(trim($diagnostic, "\n"), 1);
     
        //sort this diagnostic by bitPosition 
        if ($diagnosticBits[$bitPosition] == 1) {
            array_push($list1, $diagnostic);
        } else {
            array_push($list0, $diagnostic);
        }
    }
    
    return array("list0" => $list0, "list1" => $list1);
} 

function findDiagnostic($list, $diagnosticType) {
    $iteration = 0;
    
    while (count($list) > 1) {
        $lists = runDiagnostics($list, $iteration);
        
        if (count($lists["list1"]) > count($lists["list0"]))  {
            $list = $diagnosticType == "oxygen" ? $lists["list1"] : $lists["list0"];
        } elseif (count($lists["list1"]) < count($lists["list0"])) {
            $list = $diagnosticType == "oxygen" ? $lists["list0"] : $lists["list1"];
        } else {
            $list = $diagnosticType == "oxygen" ? $lists["list1"] : $lists["list0"];
        }
        
        $iteration++;
    }
    
    return $list[0]; 
}

$oxygenGenerator = findDiagnostic($diagnosticsList, "oxygen");
$c02 = findDiagnostic($diagnosticsList, "c02");

$oxygenDec = bindec($oxygenGenerator);
$c02Dec = bindec($c02);
$answer = $c02Dec*$oxygenDec;

echo "The answer is $answer";
?>
