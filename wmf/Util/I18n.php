<?php

namespace Util;


class I18n {

    public $currentLang;

    private $languages;
    private $dictionaries;
    private $defaultLanguage;

    public function __construct()
    {
        $config = \App::obj()->getConfig('i18n');
        $this->languages = $config['languages'];
        $this->defaultLanguage = $config['default'];
        $this->setLanguage();
        $this->loadDictionaries();
    }

    private function setLanguage()
    {
        $langInCookies = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $this->languages);
        if (!$langInCookies) {
            setcookie("lang", $this->defaultLanguage, time() + 3600 * 24 * 30, "/");
            $this->currentLang = $this->defaultLanguage;
        } else {
            $this->currentLang = $_COOKIE['lang'];
        }
    }

    private function loadDictionaries()
    {
        foreach ($this->languages as $lang) {
            $file = BASE_DIR . DIRECTORY_SEPARATOR . 'i18n'. DIRECTORY_SEPARATOR . $lang . '.php';
            $this->dictionaries[$lang] = include($file);
        }
    }

    public function m($id)
    {
        if (isset($this->dictionaries[$this->currentLang][$id])) {
            return $this->dictionaries[$this->currentLang][$id];
        }
        return $id;
    }

    public function getLanguages() {
        return $this->languages;
    }
}