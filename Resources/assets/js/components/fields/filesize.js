

const FileSizeField = (() => {

	function init(){

		var NumberField = jsGrid.NumberField;

		function field(config) {
		    NumberField.call(this, config); 
		}

		field.prototype = new NumberField({

			editing: false,
			filtering: false,
			inserting: false,
			align:'left',

		    itemTemplate: function(value) {
		        let int = parseInt(value);
		        int = Math.round(int * 0.000001);
		        let data;
		        if(int == 0){
		        	let data = value * 0.001;
		        	return data.toFixed(1) + ' Kb';
		        }
		        data = value * 0.000001;
		        return data.toFixed(1) + ' Mb';
		    }
		});

		jsGrid.fields.filesize = jsGrid.FileSize = field;

	};

	return {
		init: init
	};

})();

export default FileSizeField;