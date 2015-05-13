<?php
namespace Laravel\FormManager\Renders;

abstract class Render
{
    private $defaultMethod = 'basic';
    private $visualRequired;

    public static function get($render)
    {
        $render = __NAMESPACE__.'\\'.ucfirst($render);

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
            $input->label('<strong>'.$input->label().' *</strong>');
        } elseif ($value = $input->attr('placeholder')) {
            $input->attr('placeholder', $value.' *');
        }

        return $this;
    }

    public function label($input)
    {
        return isset($input->label) ? $input->label : null;
    }
}
