import datetimepicker from 'tempusdominus-bootstrap-4';

const DatetimeField = (() => {

    function init()
    {

        var TextField = jsGrid.TextField;

        function field(config)
        {
            this.format = config.format ? config.format : 'YYYY-MM-DD HH:mm:ss';
            TextField.call(this, config);

        }

        field.prototype = new jsGrid.TextField(
            {

                css: "datetime-field",
         
                sorter: function (date1, date2) {
                    return moment(date1) - moment(date2);
                },
         
                itemTemplate: function (value) {
                    return moment(value.date).format(this.format);
                },
         
                editTemplate: function (value) {
                    if(!this.editing) {
                        return this.itemTemplate(value);
                    }

                    this._picker = this._createInput('datetimepicker'+this.name, 'Date', value.date);

                    this._picker.datetimepicker(
                        {
                            format: this.format,
                            defaultDate: moment()
                        }
                    );
                    return this._picker;
                },

                filterTemplate: function () {
                    if(!this.filtering) {
                        return "";
                    }

                    var grid = this._grid,
                    $result = $('<div>');

                    var from = this.filterControlFrom = this._createInput('datetimepickerFrom'+this.name, 'From','').appendTo($result);
                    var to = this.filterControlTo = this._createInput('datetimepickerTo'+this.name, 'To','').appendTo($result);

                    from.datetimepicker(
                        {
                            format: this.format,
                            allowInputToggle: true,
                            sideBySide: true
                        }
                    );
                    to.datetimepicker(
                        {
                            format: this.format,
                            allowInputToggle: true,
                            sideBySide: true
                        }
                    );

                    if(this.autosearch) {
                        let grid = this._grid;
                        to.on(
                            "change.datetimepicker", function (e) {
                                grid.search();
                            }
                        );
                        from.on(
                            "change.datetimepicker", function (e) {
                                grid.search();
                            }
                        );
                    }

                    return $result;
                },

                filterValue: function () {
                    return {
                        from: this.filterControlFrom.find('input.datetimepicker-input').val(),
                        to: this.filterControlTo.find('input.datetimepicker-input').val()
                    };
                },

                _createInput(name, placeholder,value){
                    return $('<div class="input-group date" id="'+name+'" data-target-input="nearest"><input type="text" class="form-control datetimepicker-input" data-target="#'+name+'" placeholder="'+placeholder+'" value="'+value+'"/><div class="input-group-append" data-target="#'+name+'" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div></div>');
                },
         
                editValue: function () {
                    return this._picker.find('input.datetimepicker-input').val();
                }
            }
        );

        jsGrid.fields.datetime = jsGrid.DatetimeField = field;

    };

    return {
        init: init
    };

})();

export default DatetimeField;