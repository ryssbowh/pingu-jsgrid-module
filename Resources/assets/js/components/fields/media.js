const MediaField = (() => {

    function init(){

        var TextField = jsGrid.TextField;

        function field(config) {

            TextField.call(this, config);

        }

        field.prototype = new jsGrid.TextField({

            css: "media-field",
            filtering: false,
            sorting: false,
            editing: false,
         
            itemTemplate: function(value) {
                return this._createImage(value);
            },
         
            editTemplate: function(value) {
                return this._createImage(value);
            },

            _createImage(value){
                if(!value) return '';
                let icon = value;
                let link = value;
                if(typeof value === 'object'){
                    icon = value.icon;
                    link = value.media;
                }
                return $('<img src="'+icon+'">').click(function(){
                    window.open(link);
                    return false;
                });
            }
        });

        jsGrid.fields.media = jsGrid.MediaField = field;

    };

    return {
        init: init
    };

})();

export default MediaField;