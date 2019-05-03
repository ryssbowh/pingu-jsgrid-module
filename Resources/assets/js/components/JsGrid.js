import jsGrid from 'jsgrid';
import DatetimeField from './fields/datetime.js';
import SelectField from './fields/select.js';
import ModelField from './fields/model.js';

const JsGrid = (() => {

	let options = {
		jsgrid: $('.jsgrid-table'),
	};

	function init(){ 
		console.log('JsGrid initialized');

		if(options.jsgrid.length){

			SelectField.init();
			DatetimeField.init();
			ModelField.init();

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
	        	}).fail(function(data){
	        		showErrors(data.responseJSON.errors);
	        	});
			},
			updateItem: function(item){
				console.log(item);
				return $.ajax({
			        type: "PUT",
			        url: jsOptions.ajaxUrl,
			        data: item,
		       	}).fail(function(data){
	        		showErrors(data.responseJSON.errors);
	        	});
			},
			deleteItem: function(item){
				return $.ajax({
			        type: "DELETE",
			        url: jsOptions.ajaxUrl,
			        data: {id:item.id},
		       	}).fail(function(data){
	        		showErrors(data.responseJSON.errors);
	        	});
			}
		};

		options.jsgrid.jsGrid(jsOptions);
	};

	function showErrors(errors){
		var text = "";
		$.each(errors, function(item){
			text += errors[item] + "\n";
		});
		alert(text);
	}

	return {
		init: init
	};

})();

export default JsGrid;