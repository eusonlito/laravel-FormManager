<?php
namespace Eusonlito\LaravelFormManager\Renders;

abstract class Render
{
    private $defaultMethod = 'basic';
    private $visualRequired;

    public static function get($render)
    {
        return new $render();
    }

    public function __invoke($input)
    {
        $method = $input->getElementName();
        $method = ($method === 'input') ? $input->attr('type') : $method;

        if (!method_exists($this, $method)) {
            $method = $this->defaultMethod;
        }

        return $this->$method($input);
    }

    public function hidden($input)
    {
        return $input->__toString();
    }

    public function setVisualRequired($required)
    {
        $this->visualRequired = $required;

        return $this;
    }

    public function visualRequired($input)
    {
        if (!$this->visualRequired || !$input->attr('required')) {
            return $this;
        }

        if ($this->label($input)) {
            $this->visualRequiredLabel($input);
        } else {
            $this->visualRequiredPlaceholder($input);
        }

        return $this;
    }

    public function visualRequiredLabel($input)
    {
        $label = $input->label();

        if ($label && strpos($label, ' *</strong>') === false) {
            $input->label('<strong>'.$label.' *</strong>');
        }
    }

    public function visualRequiredPlaceholder($input)
    {
        $value = $input->attr('placeholder');

        if ($value && (strpos($value, ' *') === false)) {
            $input->attr('placeholder', $value.' *');
        }
    }

    public function label($input)
    {
        return (isset($input->label) && strip_tags($input->label)) ? $input->label : null;
    }
}
