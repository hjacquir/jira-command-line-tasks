<?php

namespace Hj\Jql;

class Condition
{
    /**
     * @var string
     */
    private $content;

    /**
     * @param string $content
     */
    function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}