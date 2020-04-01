<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="strict-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
