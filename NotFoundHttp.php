<?php

class NotFoundHttp implements PageInterface
{

    #[\Override] public function getHtml(): string
    {
        return "<h1>404 Not Found</h1>";
    }
}