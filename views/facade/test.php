<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\VarDumper;
echo 'post';
//VarDumper::dump($_POST, 10, true);
echo 'files';
//VarDumper::dump($_FILES, 10, true);

//VarDumper::dump(Yii::$app->hydra->ls('/'), 10, true);
//VarDumper::dump(Yii::$app->hydra->ls('/dir'), 10, true);
//VarDumper::dump(Yii::$app->hydra->clearFileCache('/vlcsnap-2015-07-19-20h40m51s369.png'), 10, true);
//VarDumper::dump(Yii::$app->hydra->getFileInfo('/vlcsnap-2015-07-19-20h40m51s369.png'), 10, true);
//VarDumper::dump(Yii::$app->hydra->deleteFile('/dir/file2.jpg'), 10, true);
//VarDumper::dump(Yii::$app->hydra->deleteFile('/vlcsnap-2015-07-19-20h40m51s369.png'), 10, true);
//<?= Html::fileInput('file') ?>
?>
<h1><?=$name?></h1>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>


<?=\zabachok\hydra\widgets\FileInput::widget([
    'name'=>'file'
])?>

    <button>Submit</button>

<?php ActiveForm::end() ?>