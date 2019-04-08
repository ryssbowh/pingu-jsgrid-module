import datetimepicker from 'tempusdominus-bootstrap-4';

const DatetimeField = (() => {

    function init(){

        var TextField = jsGrid.TextField;

        function field(config) {

            TextField.call(this, config);

        }

        field.prototype = new jsGrid.TextField({

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
                if(!this.editing){
                    return this.itemTemplate(value);
                }

                this._picker = this._createInput('datetimepickerEdit'+this.name, 'Date', value);

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

                var from = this.filterControlFrom = this._createInput('datetimepicker'+this.name, 'From','').appendTo($result);
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

            _createInput(name, placeholder,value){
                return $('<div class="input-group date" id="'+name+'" data-target-input="nearest"><input type="text" class="form-control datetimepicker-input" data-target="#'+name+'" placeholder="'+placeholder+'" value="'+value+'"/><div class="input-group-append" data-target="#'+name+'" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div></div>');
            },
         
            editValue: function() {
                return this._picker.find('input.datetimepicker-input').val();
            }
        });

        jsGrid.fields.datetime = jsGrid.DatetimeField = field;

    };

    return {
        init: init
    };

})();

export default DatetimeField;