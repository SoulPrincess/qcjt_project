<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="company-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
