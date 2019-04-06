
const JsGridFields = (() => {

	function init(){

		jsGrid.fields.email = jsGrid.fields.text;

	    var MyDateField = function(config) {
	        jsGrid.Field.call(this, config);
	    };
	     
	    MyDateField.prototype = new jsGrid.Field({
	     
	        css: "datetime-field",
	        format: (this.format ? this.format : 'YYYY-MM-DD HH:mm:ss'),
	     
	        sorter: function(date1, date2) {
	            return moment(date1) - moment(date2);
	        },
	     
	        itemTemplate: function(value) {
	            return moment(value).format(this.format);
	        },
	     
	        editTemplate: function(value) {
	        	this._picker = $('<div class="input-group date" id="datetimepicker1" data-target-input="nearest"><input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" value="'+value+'"/><div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div></div>');

                this._picker.datetimepicker({
                	format: this.format,
                	defaultDate: moment()
                });
	            return this._picker;
	        },

	        filterTemplate: function() {
	            if(!this.filtering)
	                return "";

	            var grid = this._grid,
	                $result = $('<div>');

	            var from = this.filterControlFrom = this._createInput('datetimepicker'+this.name, 'From').appendTo($result);
	            // var $to = this.filterControlTo = this._createInput('to', 'To').appendTo($result);

	            $('#datetimepicker'+this.name).datetimepicker({
                	format: 'YYYY MM DD HH:mm:ss',
                	defaultDate: moment(),
                	allowInputToggle:true
                });

	            return $result;
			},

			filterValue: function() {
				// console.log(this.filterControlFrom.find('input').val());
            	//return this.filterControl.val();
			},

			_createInput(name, placeholder){
				return $('<div class="input-group date" id="'+name+'" data-target-input="nearest"><input type="text" class="form-control datetimepicker-input" data-target="#'+name+'" placeholder="'+placeholder+'"/><div class="input-group-append" data-target="#'+name+'" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div></div>');
			},
	     
	        editValue: function() {
	        	return null;
	        }
	    });
	     
	    jsGrid.fields.datetime = MyDateField;

	    var ModelField = function(config) {
	        jsGrid.SelectField.call(this, config);
	    };

	    ModelField.prototype = new jsGrid.SelectField({
	    	itemTemplate: function(value) {
	            var items = this.items,

	            result = items[value.id];

	            return (result === undefined || result === null) ? "" : result;
			},

			filterTemplate: function() {
	            if(!this.filtering)
	                return "";

	            var grid = this._grid,
	                $result = this.filterControl = this._createSelect(true);

	            if(this.autosearch) {
	                $result.on("change", function(e) {
	                    grid.search();
	                });
	            }

	            return $result;
			},

			_createSelect: function(allowNoValue = false) {
	            var $result = $("<select>");

	            $.each(this.items, function(index, item) {
	                var $option = $("<option>")
	                    .attr("value", index)
	                    .text(item)
	                    .appendTo($result);
	            });

	            $result.prop("disabled", !!this.readOnly);
				
	            return $result;
			}

	    });

	    jsGrid.fields.model = ModelField; 

	};

	return {
		init: init
	};

})();

export default JsGridFields;