<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="guan-navigation-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
