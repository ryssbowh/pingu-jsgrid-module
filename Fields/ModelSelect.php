<?php
namespace Pingu\Jsgrid\Fields;

use Pingu\Forms\Contracts\HasItemsField;
use Pingu\Forms\Contracts\HasModelField;

class ModelSelect extends Select
{
    public function __construct(string $name, array $options, HasModelField $field)
    {
        parent::__construct($name, $options, $field);
        $this->options['multiple'] = $field->isMultiple();
        $this->options['valueField'] = $field->getModel()::keyName();
    }
}