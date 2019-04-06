import jsGrid from 'jsgrid';
import JsGridFields from './JsGrid-fields.js';

const JsGrid = (() => {

	let options = {
		jsgrid: $('.jsgrid-table'),
	};

	function init(){ 
		console.log('JsGrid initialized');
		if(options.jsgrid.length){
			JsGridFields.init();
			initJsGrid();
		}
	};

	function initJsGrid(){
		let jsOptions = options.jsgrid.data('options');
		let jsExtraOptions = options.jsgrid.data('extraoptions');
		jsOptions.fields = options.jsgrid.data('fields');
		jsOptions.rowClick = function(params){
			window.location.href = jsOptions.editUrl+'/'+params.item.id;
		};
		jsOptions.onDataLoading = function(params){
			params.filter.fields = params.grid.getFilter();
		};
		jsOptions.controller = {
			loadData: function(filter){
				return $.ajax({
		            type: "POST",
		            url: jsOptions.ajaxUrl,
		            data: {filters:filter, options:jsExtraOptions}
	        	}).done(function(data){
		        	$('.jsgrid-total').html(data.total);
		        	$('.jsgrid-total').parent().show();
	        	});
			},
			updateItem: function(item){
				return $.ajax({
			        type: "PUT",
			        url: jsOptions.ajaxUrl,
			        data: item,
		       	});
			},
			deleteItem: function(item){
				return $.ajax({
			        type: "DELETE",
			        url: jsOptions.ajaxUrl,
			        data: {id:item.id},
		       	});
			}
		};

		options.jsgrid.jsGrid(jsOptions);
	};

	return {
		init: init
	};

})();

export default JsGrid;