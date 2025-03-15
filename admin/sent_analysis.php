<?php
    include('sentiment/vendor/autoload.php');
    Use Sentiment\Analyzer;
    $analyzer = new Analyzer(); 
    
    $output_text = $analyzer->getSentiment("It is good but there are need to change a lot.");
    print_r($output_text);
    // Access the sentiment values from the output
    $positive = $output_text['pos'];
    $negative = $output_text['neg'];
    $neutral = $output_text['neu'];
    echo "<br><br>";
    if($positive >= 0.4){
        echo "Posive : ".$positive." ";
        echo '<img src="../project images/smile.png" alt="" style="width: 50px; height: 50px;">';
    }
    else if($negative >= 0.4){
        echo "Negative : ".$negative." ";
        echo '<img src="../project images/sad.png" alt="" style="width: 50px; height: 50px;">';
    }
    else if($neutral >= 0.3){
        echo "Neutral : ".$negative." ";
        echo '<img src="../project images/neutral.png" alt="" style="width: 50px; height: 50px;">';
    }
?>