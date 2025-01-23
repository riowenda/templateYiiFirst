<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Html;

$this->title = 'Oops! ada kesalahan.';
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-warning">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Maaf, terjadi kesalahan saat memproses permintaan Anda.
    </p>
    <p>
        Silakan coba lagi nanti atau hubungi tim support kami jika masalah berlanjut.
    </p>
    <br>
    <button class="btn btn-primary"><a href="javascript:history.back()">Kembali ke Halaman Sebelumnya</a></button>

</div>