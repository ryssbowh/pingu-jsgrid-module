import jsGrid from 'jsgrid';
import jsGridBase from './JsGrid';

import * as h from 'PinguHelpers';

const JsGridModel = (() => {

	let options = {
		jsgrid: $('.jsgrid-table-model'),
	};

	let jsOptions;

	function init(){ 
		if(options.jsgrid.length){
			jsGridBase.initFields();
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

	function removeNonEditableFields(item)
	{
		var value = {};
		Object.keys(item).forEach(function(name){
			if(options.jsgrid.jsGrid('fieldOption', name, 'editing')){
				value.name = item.name
			}
		});
		return value;
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
			updateItem: function(item, test){
				let url = replaceUriToken(jsOptions.ajaxUpdateUri, item);
				let d = $.Deferred();
				item = removeNonEditableFields(item);
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

	return {
		init: init
	};

})();

export default JsGridModel;