<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="qing-case-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
