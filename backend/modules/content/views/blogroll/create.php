<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="blogroll-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
