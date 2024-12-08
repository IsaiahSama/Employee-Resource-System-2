<?php

namespace Framework\Views;

interface FormGenerator {
    public function validate($form) : bool;
}
