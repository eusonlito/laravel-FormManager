<?php
namespace Eusonlito\LaravelFormManager\Renders;

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

        return '<div class="form-group">'
            .'<div class="checkbox-inline">'
            .'<label>'
            .'<input type="hidden" name="'.$input->attr('name').'" value="" />'
            .$input->input.($label ?: $input->attr('placeholder'))
            .'</label></div></div>';
    }

    public function select($input)
    {
        if (!($related = $input->data('related'))) {
            return $this->basic($input);
        }

        $input->addClass('form-control');

        $this->visualRequired($input);

        $input->data('related', false);

        $html = $this->label($input)
            .'<div class="input-group form-group" data-related>'
            .$input->input;

        return $html.'<span class="input-group-btn">'
            .'<a href="'.$related.'" class="btn btn-primary">'
            .'<i class="glyphicon glyphicon glyphicon-share-alt"></i>'
            .'</a></span></div>';
    }

    public function file($input)
    {
        $input->addClass('form-control');

        $this->visualRequired($input);

        if (!($value = $input->val()) || !is_string($value)) {
            return '<div class="form-group">'.$input.'</div>';
        }

        $html = $this->label($input).'<div class="input-group form-group">'.$input->input;

        if (!strstr($value, '?')) {
            $value = asset('storage/resources/'.$value);
        }

        return $html.'<span class="input-group-btn">'
            .'<a href="'.$value.'" target="_blank" class="btn btn-primary">'
            .'<i class="glyphicon glyphicon-eye-open"></i>'
            .'</a></span></div>';
    }
}
