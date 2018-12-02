<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class AvailableParent implements Rule
{

    /**
     * @var Model $model
     */
    protected $model;

    /**
     * @var string $message
     */
    protected $message = '';

    /**
     * Create a new rule instance.
     *
     * AvailableParent constructor.
     * @param Model $model
     *
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     * Model must have getChildren method, which will be return Collection
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!method_exists($this->model, 'getChildren')) {
            $this->message = 'Current model has not method "getChildren" for validate parent attribute';
            return false;
        }
        if (!is_integer($value)) {
            $this->message = 'Value must be only integer type';
            return false;
        }
        foreach ($this->model->getChildren() as $child) {
            if ($child->id == $value) {
                $this->message = 'You can\'t set parent = ' . $value . ' for this model';
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
