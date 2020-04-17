<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="guan-goods-attribute-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
