<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

class PostHtmlSanitizer
{
    private static ?HTMLPurifier $purifier = null;

    public static function clean(string $html): string
    {
        return self::purifier()->purify($html);
    }

    private static function purifier(): HTMLPurifier
    {
        if (self::$purifier !== null) {
            return self::$purifier;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.Allowed', 'p,br,b,strong,i,em,u,s,ul,ol,li,blockquote,h1,h2,h3,a[href|title|target],code,pre,div,span');
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.AutoParagraph', false);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));

        if (! is_dir(storage_path('app/htmlpurifier'))) {
            @mkdir(storage_path('app/htmlpurifier'), 0775, true);
        }

        return self::$purifier = new HTMLPurifier($config);
    }
}
