<?php

namespace Yandex\Translate;
class Translation
{
    
    protected $source;
    protected $result;
    protected $language;

    public function __construct($source, $result, $language)
    {
        $this->source = $source;
        $this->result = $result;
        $this->language = explode('-', $language);
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getResult()
    {
        return $this->result;
    }
    // return SourceLanguge
    public function getSourceLanguage()
    {
        return $this->language[0];
    }
    // the translation languge
    public function getResultLanguage()
    {
        return $this->language[1];
    }
    public function __toString()
    {
        if (is_array($this->result)) {
            return join(' ', $this->result);
        }

        return (string) $this->result;
    }
}


