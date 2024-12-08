<?php

namespace Framework\Views;

class FormValidator {
    public function boolValidate($form) : bool {
        return $this->allFieldsExist($form, array_keys($form)); // Will need to be subclassed.
    }

    public function validate($form) {
        return $this->allFieldsExist($form, array_keys($form));
    }

    public function allFieldsExist($form, $fields) : bool{
        foreach ($fields as $field) {
            if (!isset($form[$field]) || empty($form[$field])) {
                return false;
            }
        }
        return true;
    }

    public function customValidate($form, $validateFunc) : bool {
        // Note: ValidateFunc is expected to return a Boolean.
        return $validateFunc($form);
    }
}
