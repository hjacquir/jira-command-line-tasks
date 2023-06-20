<?php

declare(strict_types=1);

namespace App\Validator\YamlFile\KeyValueValidator;

use App\File\JqlFile;

class Jql extends KeyValueValidator
{
    protected function isValid($value)
    {
        $this->validKey($value, JqlFile::KEY_PROJECT);
        $this->validKey($value, JqlFile::KEY_CONDITIONS);
        $this->validKey($value, JqlFile::KEY_EXPRESSIONS);
        $this->validKey($value[JqlFile::KEY_PROJECT], JqlFile::KEY_NAME);
        $this->validKey($value[JqlFile::KEY_PROJECT], JqlFile::KEY_OPERATOR);
        $this->validKey($value[JqlFile::KEY_PROJECT], JqlFile::KEY_ISSUE_SUFFIX);
        $this->valueIsArray($value[JqlFile::KEY_PROJECT], JqlFile::KEY_PROJECT);
        $this->valueIsArray($value[JqlFile::KEY_CONDITIONS], JqlFile::KEY_CONDITIONS);
        $this->valueIsArray($value[JqlFile::KEY_EXPRESSIONS], JqlFile::KEY_EXPRESSIONS);
        $this->valueIsString($value[JqlFile::KEY_PROJECT][JqlFile::KEY_NAME], JqlFile::KEY_NAME);
        $this->valueIsString($value[JqlFile::KEY_PROJECT][JqlFile::KEY_OPERATOR], JqlFile::KEY_OPERATOR);
        $this->valueIsString($value[JqlFile::KEY_PROJECT][JqlFile::KEY_ISSUE_SUFFIX], JqlFile::KEY_ISSUE_SUFFIX);
    }
}
