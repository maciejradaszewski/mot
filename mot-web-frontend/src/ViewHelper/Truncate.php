<?php
namespace Dvsa\Mot\Frontend\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class Truncate extends AbstractHelper
{
    public function __invoke($text, $length, $postfix = '...', $wordsafe = true, $escape = true)
    {
        if (strlen($text) <= $length)
            return $escape ? $this->view->escapeHtml($text) : $text;

        if (!$wordsafe) {
            $text = substr($text, 0, $length);
        } else {
            $text   = substr($text, 0, $length + 1);
            $length = strrpos($text, ' ');
            $text   = substr($text, 0, $length);

            preg_match('/(.*?)(?:[^a-zA-Z0-9])*$/', $text, $match);
            $text = $match[1];
        }

        $text .= $postfix;

        return ($escape ? $this->view->escapeHtml($text) : $text);

    }
}
