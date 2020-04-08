<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="company-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
