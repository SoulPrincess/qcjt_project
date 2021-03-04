<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="qing-case-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
