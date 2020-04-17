<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="guan-navigation-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
