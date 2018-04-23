(function() {
    tinymce.create('tinymce.plugins.video_tlw', {
        init : function(ed, url) {
            ed.addButton('video_tlw', {
                title : 'Aggiungi video',
                image : url+"/video_tlw.png",
                onclick : function() {
                     ed.selection.setContent('[video_tlw url="" autoplay="true" width="100%" height="300" mute="false" repeat="false"]' + ed.selection.getContent());
 
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('video_tlw', tinymce.plugins.video_tlw);
})();