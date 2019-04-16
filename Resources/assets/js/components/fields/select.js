

const SelectField = (() => {

	function init(){

		var NumberField = jsGrid.NumberField;
		var numberValueType = "number";
		var stringValueType = "string";

		function field(config) {
		    this.items = [];
		    this.selectedIndex = -1;
		    this.valueField = "";
		    this.textField = "";

		    if(config.valueField && config.items.length) {
		        var firstItemValue = config.items[0][config.valueField];
		        this.valueType = (typeof firstItemValue) === numberValueType ? numberValueType : stringValueType;
		    }

		    this.sorter = this.valueType;

		    NumberField.call(this, config); 
		}

		field.prototype = new NumberField({

		    valueType: numberValueType,
		    align: 'left',

		    itemTemplate: function(value) {
		        var items = this.items,
		            valueField = this.valueField,
		            textField = this.textField,
		            resultItem;

		        if(valueField) {
		            resultItem = $.grep(items, function(item, index) {
		                return item[valueField] === value;
		            }) || {};
		        }
		        else {
		            resultItem = items[value];
		        }

		        var result = (textField ? resultItem[textField] : resultItem);

		        return (result === undefined || result === null) ? "" : result;
		    },

		    filterTemplate: function() {
		        if(!this.filtering)
		            return "";

		        var grid = this._grid,
		            $result = this.filterControl = this._createSelect();

		        if(this.autosearch) {
		            $result.on("change", function(e) {
		                grid.search();
		            });
		        }

		        return $result;
		    },

		    insertTemplate: function() {
		        if(!this.inserting)
		            return "";

		        return this.insertControl = this._createSelect();
		    },

		    editTemplate: function(value) {
		        if(!this.editing)
		            return this.itemTemplate.apply(this, arguments);

		        var $result = this.editControl = this._createSelect();
		        (value !== undefined) && $result.val(value);
		        return $result;
		    },

		    filterValue: function() {
		        var val = this.filterControl.val();
		        return this.valueType === numberValueType ? parseInt(val || 0, 10) : val;
		    },

		    insertValue: function() {
		        var val = this.insertControl.val();
		        return this.valueType === numberValueType ? parseInt(val || 0, 10) : val;
		    },

		    editValue: function() {
		        var val = this.editControl.val();
		        return this.valueType === numberValueType ? parseInt(val || 0, 10) : val;
		    },

		    _createSelect: function() {
		        var $result = $("<select>"),
		            valueField = this.valueField,
		            textField = this.textField,
		            selectedIndex = this.selectedIndex;

		        $.each(this.items, function(index, item) {
		            var value = valueField ? item[valueField] : index,
		                text = textField ? item[textField] : item;

		            var $option = $("<option>")
		                .attr("value", value)
		                .text(text)
		                .appendTo($result);

		        });

		        $result.prop("disabled", !!this.readOnly);
		        $result.prop("selectedIndex", selectedIndex);
				
		        return $result;
		    }
		});

		jsGrid.fields.select = jsGrid.SelectField = field;

	};

	return {
		init: init
	};

})();

export default SelectField;