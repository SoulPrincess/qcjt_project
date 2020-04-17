<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="guan-type-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
