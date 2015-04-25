<?php

namespace App\Library\Forms\Renders;

class Bootstrap extends Render
{
    public function basic($input)
    {
        $input->addClass('form-control');

        $this->visualRequired($input);

        return '<div class="form-group">'.$input.'</div>';
    }

    public function checkbox($input)
    {
        $this->visualRequired($input);

        $label = $this->label($input);

        return '<div class="checkbox-inline form-group">'
            .'<label>'
            .$input->input.($label ?: $input->attr('placeholder'))
            .'</label></div>';
    }

    public function file($input)
    {
        $input->addClass('form-control');

        $this->visualRequired($input);

        if (!($value = $input->data('value'))) {
            return '<div class="form-group">'.$input.'</div>';
        }

        $html = $this->label($input).'<div class="input-group form-group">'.$input->input;

        if (!strstr($value, '?')) {
            $value = url('storage/resources/'.$value);
        }

        return $html.'<span class="input-group-btn">'
            .'<a href="'.$value.'" target="_blank" class="btn btn-primary">'
            .'<i class="glyphicon glyphicon-eye-open"></i>'
            .'</a></span></div>';
    }
}
