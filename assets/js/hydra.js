/**
 * Created by zabachok on 28.01.16.
 */
window.hydra = {
    current: {}
};

hydra.facade = {
    root: '',

    init : function()
    {
        this.ls('');
    },

    ls: function (dir)
    {
        var path = hydra.facade.root + dir;
        //if (hydra.current.path = path) return;
        $.get('/hydra/api/ls', {path: path}, function (data) {
            hydra.facade.printTree(data.path, data.folders);
            hydra.facade.printBreadcrumbs(data.breadcrumbs);
            hydra.facade.printFiles(data.files);
        });
    },
    printTree : function(path, folders)
    {
        var box = $('#hydra-tree').find('li[data-path="' + path + '"]');
        if(box.children('ul').length != 0) box.children('ul').remove();
        box.append('<ul></ul>');
        if(folders.length == 0) return;
        if(path == '') path = '/';
        var myUl = box.children('ul');
        for(var i = 0; i < folders.length; i++)
        {
            var itemPath = path + folders[i].name;
            var html = '<li class="root" data-path="' + itemPath + '">' +
            '<a href="javascript:hydra.facade.ls(\'' + itemPath + '\')">' + folders[i].name + '</a>' +
            '</li>';
            myUl.append(html);
        }
    },
    printBreadcrumbs : function(breadcrumbs)
    {
        var box = $('#hydra-breadcrumbs');
        box.empty().append('<li><a href="javascript:hydra.facade.ls(\'\')" class="rootLabel">Корень</a></li>');
        if(breadcrumbs.length == 0) return;
        for(var i = 0; i < breadcrumbs.length; i++)
        {
            var html = '<li class="root" data-path="' + breadcrumbs[i].path + '">' +
                '<a href="javascript:hydra.facade.ls(\'' + breadcrumbs[i].path + '\')">' + breadcrumbs[i].label + '</a>' +
                '</li>';
            box.append(html);
        }
    },
    printFiles : function(files)
    {
        for(var i = 0; i < files.length; i++)
        {
            $('#hydra-dirplane').append(files[i].view);
        }
    }
};