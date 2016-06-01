<div>
    <div class="btn-group">
        <button type="button" class="btn btn-default" onclick="burivuh.write.textFormatting.setHeader();">
            <i class="glyphicon glyphicon-header"></i>
        </button>
        <button type="button" class="btn btn-default" onclick="burivuh.write.textFormatting.setBold();">
            <i class="glyphicon glyphicon-bold"></i>
        </button>
        <button type="button" class="btn btn-default" onclick="burivuh.write.textFormatting.setItalic();">
            <i class="glyphicon glyphicon-italic"></i>
        </button>
        <button type="button" class="btn btn-default" onclick="burivuh.write.textFormatting.setBoldItalic();">
            <i class="glyphicon glyphicon-bold"></i>+<i class="glyphicon glyphicon-italic"></i>
        </button>
        <button type="button" class="btn btn-default" onclick="burivuh.write.textFormatting.setLink();">
            <i class="glyphicon glyphicon-link"></i>
        </button>
        <button type="button" class="btn btn-default" onclick="$('#burivuh-link-form').slideUp();$('#burivuh-picture-form').slideToggle();">
            <i class="glyphicon glyphicon-picture"></i>
        </button>
    </div>
    <div id="burivuh-link-form" style="display: none;">
        <div class="input-group">
            <span class="input-group-addon"><?=Yii::t('burivuh', 'Link label')?></span>
            <input type="text" class="form-control" name="label">
            <span class="input-group-addon"><?=Yii::t('burivuh', 'Link url')?></span>
            <input type="text" class="form-control" name="url">
            <div class="input-group-btn">
                <div class="btn btn-primary" onclick="burivuh.write.textFormatting.insertLink()"><?=Yii::t('burivuh', 'Insert')?></div>
            </div>
        </div>
    </div>
    <div id="burivuh-picture-form" style="display: none;">
        <div class="input-group">
            <span class="input-group-addon"><?=Yii::t('burivuh', 'Picture label')?></span>
            <input type="text" class="form-control" name="alt">
            <span class="input-group-addon"><?=Yii::t('burivuh', 'Picture url')?></span>
            <input type="text" class="form-control" name="url">
            <div class="input-group-btn">
                <div class="btn btn-primary" onclick="burivuh.write.textFormatting.insertPicture()"><?=Yii::t('burivuh', 'Insert')?></div>
            </div>
        </div>
    </div>

</div>
<?=$input?>