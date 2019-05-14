<?php

namespace Yandex\Translate;

class Translator
{
    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';
    const MESSAGE_UNKNOWN_ERROR = 'Unknown error';
    const MESSAGE_JSON_ERROR = 'JSON parse error';
    const MESSAGE_INVALID_RESPONSE = 'Invalid response from service';

    protected $key = '';
    protected $handler;

    public function __construct($key)
    {
        $this->key = $key;
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
    }
    public function getSupportedLanguages($culture = null)
    {
        return $this->execute('getLangs', array(
            'ui' => $culture
        ));
    }

    public function detect($text)
    {
        $data = $this->execute('detect', array(
            'text' => $text
        ));

        return $data['lang'];
    }

    public function translate($text, $language, $html = false, $options = 0)
    {
        $data = $this->execute('translate', array(
            'text'    => $text,
            'lang'    => $language,
            'format'  => $html ? 'html' : 'plain',
            'options' => $options
        ));

        // @TODO: handle source language detecting
        return new Translation($text, $data['text'], $data['lang']);
    }

    protected function execute($uri, array $parameters)
    {
        $parameters['key'] = $this->key;
        curl_setopt($this->handler, CURLOPT_URL, static::BASE_URL . $uri);
        curl_setopt($this->handler, CURLOPT_POST, true);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, false);
        
        $remoteResult = curl_exec($this->handler);
        if ($remoteResult === false) {
            throw new Exception(curl_error($this->handler), curl_errno($this->handler));
        }

        $result = json_decode($remoteResult, true);
        if (!$result) {
            $errorMessage = self::MESSAGE_UNKNOWN_ERROR;
            if (version_compare(PHP_VERSION, '5.3', '>=')) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    if (version_compare(PHP_VERSION, '5.5', '>=')) {
                        $errorMessage = json_last_error_msg();
                    } else {
                        $errorMessage = self::MESSAGE_JSON_ERROR;
                    }
                }
            }
            throw new Exception(sprintf('%s: %s', self::MESSAGE_INVALID_RESPONSE, $errorMessage));
        } elseif (isset($result['code']) && $result['code'] > 200) {
            throw new Exception($result['message'], $result['code']);
        }

        return $result;
    }
}

