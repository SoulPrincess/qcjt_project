<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="blogroll-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
