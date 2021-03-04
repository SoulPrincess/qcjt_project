<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="guan-type-update">
    <?=
    $this->render('_form', [
        'model' => $model,
        'imgmodel' => $imgmodel,
    ])
    ?>

</div>
