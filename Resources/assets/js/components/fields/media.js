const MediaField = (() => {

    function init(){

        var TextField = jsGrid.TextField;

        function field(config) {

            TextField.call(this, config);

        }

        field.prototype = new jsGrid.TextField({

            css: "media-field",
         
            itemTemplate: function(value) {
                return this._createImage(value);
            },
         
            editTemplate: function(value) {
                return this._createImage(value);
            },

            _createImage(value){
                return $('<img src="'+value+'">').click(function(){
                    window.open(value);
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