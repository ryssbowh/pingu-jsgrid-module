import jsGrid from 'jsgrid';
import DatetimeField from './fields/datetime.js';
import SelectField from './fields/select.js';
import ModelSelectField from './fields/modelselect.js';
import MediaField from './fields/media.js';
import ArrayToString from './fields/arraytostring.js';
import * as h from 'PinguHelpers';

const JsGridModel = (() => {

	let options = {
		jsgrid: $('.jsgrid-table-model'),
	};

	let jsOptions;

	function init(){ 
		if(options.jsgrid.length){
			h.log('JsGridModel initialized');
			SelectField.init();
			DatetimeField.init();
			ModelSelectField.init();
			MediaField.init();
			ArrayToString.init();
			options.jsgrid.on('jsgrid-error', function(e, action, data){
				showErrors(data.responseJSON.message);
			});
			initJsGrid();
		}
	};

	function reorganizeFilters(filters){
		let data = {
			pageIndex: filters.pageIndex,
			pageSize: filters.pageSize,
			sortField: filters.sortField,
			sortOrder: filters.sortOrder
		};
		delete filters.pageIndex;
		delete filters.pageSize;
		delete filters.sortField;
		delete filters.sortOrder;
		data.filters = {...filters, ...jsOptions.extraFilters};
		return data;
	}

	function initJsGrid(){
		jsOptions = options.jsgrid.data('options');

		jsOptions.rowClick = function(params){
			if(jsOptions.canClick){
				window.location.href = replaceUriToken(jsOptions.clickUrl, params.item);
			}
		};

		jsOptions.controller = {
			loadData: function(filters){
				let d = $.Deferred();
				filters = reorganizeFilters(filters);
				h.get(jsOptions.ajaxIndexUri, filters)
					.done(function(data){
			        	$('.jsgrid-total').html(data.total);
			        	$('.jsgrid-total').parent().show();
			        	d.resolve({data:data.models});
		        	}).fail(function(data){
		        		options.jsgrid.trigger('jsgrid-error', ['load', data]);
		        		d.reject();
		        	});
		        return d.promise();
			},
			updateItem: function(item){
				let url = replaceUriToken(jsOptions.ajaxUpdateUri, item);
				let d = $.Deferred();
				h.put(url,item)
					.done(function(data){
						d.resolve(data.model);
					})
					.fail(function(data){
		        		options.jsgrid.trigger('jsgrid-error', ['update', data, item]);
		        		d.reject();
		        	});
		        return d.promise();
			},
			deleteItem: function(item){
				let url = replaceUriToken(jsOptions.ajaxDeleteUri, item);
				return h._delete(url)
					.fail(function(data){
		        		options.jsgrid.trigger('jsgrid-error', ['delete', data, item]);
		        	});
			}
		};

		options.jsgrid.jsGrid(jsOptions);
	};

	function replaceUriToken(url, item){
		let match = url.match(/^.*\{([a-zA-Z0-9\-_]+)\}.*$/);
		return url.replace('{'+match[1]+'}',item[match[1]]);

	}

	function showErrors(message){
		alert(message);
	}

	return {
		init: init,
		showErrors: showErrors
	};

})();

export default JsGridModel;