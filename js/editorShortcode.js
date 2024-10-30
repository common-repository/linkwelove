
(function() {
    tinymce.PluginManager.add('lwlcustom_mce_button', function(editor, url) {
        editor.addButton('lwlcustom_mce_button', {
            image: url+'/lwl.png',
            title: 'Widget LinkWeLove',
            onclick: function() {
                console.log(url);
                editor.windowManager.open({
                    title: 'Inserisci Widget LinkWeLove',
                    body: [{
                        type: 'textbox',
                        name: 'cod',
                        label: 'Codice Widget LinkWeLove',
                        value: ''
                    } ],
                    onsubmit: function(e) {
                        editor.insertContent(
                            '[lwlWidget cod="' + e.data.cod + '"]'
                        );
                    }
                });
            }
        });
    });
})();