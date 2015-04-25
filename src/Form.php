<?php

namespace Laravel\FormManager;

use Input;
use FormManager\Containers\Form as F;
use FormManager\Builder as B;

abstract class Form extends F
{
    protected static $fake = ['fake_email', 'fake_url'];

    public function setRender($render, $input = null)
    {
        $render = Renders\Render::get($render)->setVisualRequired(true);

        if ($input) {
            $input->render($render);
        } else {
            foreach ($this as $input) {
                $input->render($render);
            }
        }

        return $this;
    }

    public function preload($data)
    {
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } else {
                $data = json_decode(json_encode($data), true);
            }
        }

        return $this->load(array_merge($this->val(), $data));
    }

    public function setOptions($input, $rows, $key, $title, $empty = false)
    {
        $options = [];

        if ($empty) {
            $options[0] = is_string($empty) ? $empty : ' ------ ';
        }

        foreach ($rows as $row) {
            $options[$row->$key] = $row->$title;
        }

        return $this[$input]->clear()->options($options);
    }

    public function token()
    {
        return B::hidden()->attr('name', '_token')->value(csrf_token());
    }

    public function fake($group = true)
    {
        $fields = [];

        foreach (self::$fake as $fake) {
            if (strstr($fake, 'email')) {
                $field = B::email();
            } else {
                $field = B::text();
            }

            $fields[$fake] = $field->addClass('required')->style('display: none');
        }

        return $group ? B::group($fields) : $fields;
    }

    public function tokenAndFake()
    {
        $group = self::fake(false);
        $group['_token'] = self::token();

        return B::group($group);
    }

    public function getFake()
    {
        return self::$fake;
    }

    public function referer($url = '')
    {
        $referer = Input::get('referer') ?: ($url ?: getenv('REQUEST_URI'));

        return B::hidden()->name('referer')->value($referer);
    }
}
