<?php

class HomePage
{
    private function getTitle(): string
    {
        return '<h1>Добро пожаловать!</h1>';

    }

    private function getLinks(): string
    {
        $result = '';
        foreach (\config\Page::LINKS as $key => $link) {
            $result .= '<a href="'.$link.'">'.$key.'</a><br>';
        }

        return $result;
    }
    public function getHtml(): string
    {
        $html = $this->getTitle();
        $html .= '<br>';
        $html .= '<br>';
        $html .= $this->getLinks();

        return $html;
    }
}