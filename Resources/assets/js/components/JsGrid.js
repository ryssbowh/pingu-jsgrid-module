import jsGrid from 'jsgrid';
import DatetimeField from './fields/datetime.js';
import SelectField from './fields/select.js';
import ModelSelectField from './fields/modelselect.js';
import MediaField from './fields/media.js';
import ArrayToString from './fields/arraytostring.js';
import FileSizeField from './fields/filesize.js';
import * as h from 'PinguHelpers';

const JsGrid = (() => {

	let options = {
		jsgrid: $('.jsgrid-table'),
	};

	let jsOptions;

	function init(){ 
		if(options.jsgrid.length){
			h.log('JsGrid initialized');
			initFields();
			initJsGrid();
		}
	};

	function initFields()
	{
		SelectField.init();
		DatetimeField.init();
		ModelSelectField.init();
		MediaField.init();
		ArrayToString.init();
		FileSizeField.init();
	}

	function initJsGrid(){
		jsOptions = options.jsgrid.data('options');

		jsOptions.controller = {
            loadData: function(filter) {
                var startIndex = (filter.pageIndex - 1) * filter.pageSize;
                var data = $.grep(jsOptions.data, function(item){
                	let keep = true;
                	$.each(jsOptions.fields, function(i,field){
                		if(field.type == 'text' && filter[field.name] && item[field.name].indexOf(filter[field.name]) < 0){
                			keep = false;
                			return false;
                		}
                		if(field.type == 'select' && filter[field.name]){
                			if(item[field.name] != filter[field.name]){
                				keep = false;
                				return false;
                			}
                		}
                	});
                	return keep;
                });
                if(filter.sortField){
	                data = data.sort(function(a, b){
	                	if(filter.sortOrder == 'desc'){
	                		return a[filter.sortField] > b[filter.sortField];
	                	}
	                	return a[filter.sortField] <= b[filter.sortField];
	                });
	            }
                return {
                    data: data.slice(startIndex, startIndex + filter.pageSize),
                    itemsCount: data.length
                };
            }
        };

		options.jsgrid.jsGrid(jsOptions);
	};

	return {
		init: init,
		initFields: initFields
	};

})();

export default JsGrid;