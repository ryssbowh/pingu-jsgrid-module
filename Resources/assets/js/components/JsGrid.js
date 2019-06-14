import jsGrid from 'jsgrid';
import DatetimeField from './fields/datetime.js';
import SelectField from './fields/select.js';
import ModelSelectField from './fields/modelselect.js';
import * as h from 'pingu-helpers';

const JsGrid = (() => {

	let options = {
		jsgrid: $('.jsgrid-table'),
	};

	function init(){ 
		console.log('JsGrid initialized');

		if(options.jsgrid.length){
			SelectField.init();
			DatetimeField.init();
			ModelSelectField.init();
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
		data.filters = filters;
		return data;
	}

	function initJsGrid(){
		let jsOptions = options.jsgrid.data('options');

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
				delete item[jsOptions.primaryKey];
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
		let match = url.match(/^.*\{([a-zA-Z]+)\}.*$/);
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

export default JsGrid;