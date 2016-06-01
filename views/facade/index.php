
<?php
use zabachok\hydra\widgets\FileInput;
use zabachok\hydra\Assets;
Assets::register($this);
$this->registerJs("hydra.facade.init();");

echo FileInput::widget([
    'name' => 'mediafile',
]);
?>
<?php //return; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb" id="hydra-breadcrumbs">
                <li>Корень</li>
            </ol>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="search" class="form-control" placeholder="Поиск в этой папке">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-search"></span>
                    <span class="glyphicon glyphicon-remove" style="cursor: pointer;"></span>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-folder-close"></span> Создать папку</div>

        </div>
        <div class="col-md-3">
            <div class="progress progress-striped active" id="FMProgerssbar">
                <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
        </div>
        <div class="col-md-4" id="FMErrorLog" style="height: 20px;overflow: hidden;">
        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col-md-3">
            <div class="bs-example">
                <div class="bs-child" id="hydra-tree">
                    <ul>
                        <li class="root" data-path="">
                            <i class="glyphicon glyphicon-folder-open"></i> <a href="javascript:hydra.facade.ls('')" class="rootLabel">Корень</a>
                            <ul></ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="bs-example">
                <div class="bs-child" id="FMUploadArea">
                    <input type="file" name="files[]" multiple="multiple" title="Click to add Files" id="FMUploadInput" style=''>
                    <ul id="hydra-dirplane" class="clearfix"></ul>

                </div>
            </div>
        </div>
    </div>
</div>