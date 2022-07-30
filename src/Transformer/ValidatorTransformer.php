<?php

namespace App\Transformer;

class ValidatorTransformer
{
    public function toArray($errors): array
    {
        $result = [];
        foreach ($errors as $error) {
            $result[$error->getPropertyPath()] = $error->getMessage();
        }

        return $result;
    }
}
