<?php
$i = 0;
foreach ($itens as $v) {
    $i++;
    if($i == 1 || $i == 2){
        echo '<div class="col-md-4 col-sm-4 col-xs-4">';
        
    }else {
        echo '<div class="col-md-3 col-sm-3 col-xs-3">';
        $i = 0;
    }
    
    echo '<label class="checkbox"><input type="checkbox" name="checkbox-item[]" value="' . $v['CB11_ID'] . '" title="' . $v['CB11_DESCRICAO'] . '"><i></i>' . $v['CB11_DESCRICAO'] . '</label></div>';
    
}
?>