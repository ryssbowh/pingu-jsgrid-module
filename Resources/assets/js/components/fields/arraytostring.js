

const ArrayToStringField = (() => {

	function init(){

		var Field = jsGrid.Field;

	    function ArrayToStringField(config) {
	        Field.call(this, config);
	    }

	    ArrayToStringField.prototype = new Field({

	        filterTemplate: function() {
	            if(!this.filtering)
	                return "";

	            var grid = this._grid,
	                $result = this.filterControl = this._createTextBox();

	            if(this.autosearch) {
	                $result.on("keypress", function(e) {
	                    if(e.which === 13) {
	                        grid.search();
	                        e.preventDefault();
	                    }
	                });
	            }

	            return $result;
	        },

	        itemTemplate: function(value) {
                return value.join(',');
            },

	        editTemplate: function(value) {
	            if(!this.editing)
	                return this.itemTemplate.apply(this, arguments);

	            var $result = this.editControl = this._createTextBox();
	            $result.val(value.join(','));
	            return $result;
	        },

	        filterValue: function() {
	            return this.filterControl.val();
	        },

	        editValue: function() {
	            return this.editControl.val();
	        },

	        _createTextBox: function() {
	            return $("<input>").attr("type", "text")
	                .prop("readonly", !!this.readOnly);
	        }
	    });

		jsGrid.fields.arraytostring = jsGrid.ArrayToStringField = ArrayToStringField;

	};

	return {
		init: init
	};

})();

export default ArrayToStringField;