

const ModelField = (() => {

    function init(){

        var SelectField = jsGrid.SelectField;

        function field(config) {

            jsGrid.SelectField.call(this, config);
        }

        field.prototype = new SelectField({

            inItems: function(id){
                var found = false;
                $.each(this.items, function(index, item){
                    if(typeof item[id] != 'undefined'){
                        found = true;
                        return;
                    }
                });
                return found;
            },

            filterTemplate: function() {
                if(!this.filtering)
                    return "";

                var grid = this._grid,
                    $result = this.filterControl = this._createSelect();

                $result.prepend('<option value="">All</option>');
                $result.prop('multiple', false);
                $result.prop('selectedIndex',0);

                if(this.autosearch) {
                    $result.on("change", function(e) {
                        grid.search();
                    });
                }

                return $result;
            },

            editValue: function() {
                return this.editControl.val();
            },

            itemTemplate: function(value) {
                var items = this.items,
                    valueField = this.valueField,
                    textField = this.textField,
                    me = this,
                    separator = this.separator,
                    result;

                if(this.multiple) {
                    result = $.grep(value, function(item) {
                        return me.inItems(item[valueField]);
                    });
                    if(result.length > 0){
                        result = result.map(function(item){
                            var fields = [];
                            textField.forEach(function(field){
                                fields.push(item[field]);
                            });
                            return fields.join(separator);
                        });
                        return result.join('<br/>');
                    }
                    else{
                        result = '';
                    }
                }
                else {
                    result = $.grep(items, function(item, index) {
                        return index == value[valueField];
                    });
                    return (result.length > 0) ? result[0] : '';
                }
            },

            editTemplate: function(value) {
                var valueField = this.valueField;

                if(!this.editing)
                    return this.itemTemplate.apply(this, arguments);

                var $result = this.editControl = this._createSelect();
                if(this.multiple){
                    value = $.map(value, function(elem,ind){
                        return elem[valueField];
                    });
                }
                else{
                    value = (value !== undefined) ? value[valueField] : 0;
                }
                $result.val(value);
                return $result;
            },

            _createSelect: function() {
                var $result = $("<select>");

                $.each(this.items, function(index, item) {
                    var $option = $("<option>")
                        .attr("value", item.id)
                        .text(item.label)
                        .appendTo($result);
                });

                if(this.selectedIndex == -1){ this.selectedIndex = 0;}

                $result.prop("disabled", !!this.readOnly);
                $result.prop("selectedIndex", this.selectedIndex);
                $result.prop('multiple', this.multiple);
                
                return $result;
            }
        });

        jsGrid.fields.model = jsGrid.ModelField = field;

    };

    return {
        init: init
    };

})();

export default ModelField;