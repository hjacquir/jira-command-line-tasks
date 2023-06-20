<?php

declare(strict_types=1);

namespace App\Validator\YamlFile\KeyValueValidator;

use App\File\CascadeCommandFile;

class CascadeCommand extends KeyValueValidator
{
    protected function isValid($value)
    {
        $this->validKey($value, CascadeCommandFile::KEY_COMMANDS);

        $this->valueIsArray($value[CascadeCommandFile::KEY_COMMANDS], CascadeCommandFile::KEY_COMMANDS);

        foreach ($value[CascadeCommandFile::KEY_COMMANDS] as $item) {
          $currentIndexKeyName = array_key_first($item);
          $this->valueIsArray($item[$currentIndexKeyName], $currentIndexKeyName);
        }
    }
}
