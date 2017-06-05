<?php

namespace App\Helpers;

use League\CommonMark\CommonMarkConverter;

class Markdown
{
    public static function convert($content, $allow_html)
    {
        if($allow_html) {
            $config = ['html_input' => 'allow'];
        } else {
            $config = ['html_input' => 'escape'];
        }

        $converter = new CommonMarkConverter($config);

        return $converter->convertToHtml($content);
    }
}