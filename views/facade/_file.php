
<li class="hydra-file" data-file_id="">
    <span class="thumbnail">
        <img src="<?=$model->preview?>"><?=$model->preview?>
    </span>

    <div class="ul dropdown">
        <span class="filename pull-left" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="video-halt.jpg 200.7кб">
            video-halt
        </span>
        <span class="glyphicon glyphicon-list-alt pull-right dropdown-toggle" data-toggle="dropdown" id="dr0"></span>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dr0">
            <li role="presentation" class="disabled">
                <a role="menuitem" tabindex="-1" href="#">Копировать путь</a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="javascript:fileManager.renameFileConfirm(0);">Переименовать файл</a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="javascript:fileManager.deleteFileConfirm(0);">Удалить файл</a>
            </li>
            <li role="presentation" class="divider"></li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="javascript:fileManager.getFileProreties(0);">Свойства файла</a>
            </li>
        </ul>
    </div>
</li>