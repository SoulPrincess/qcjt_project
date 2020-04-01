<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="strict-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
