<?php

namespace Models;

use Models\Base\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $attaches = ['slug'];

    public function getSlugProperty() {
        $text = $this->title;
        $divider = '-';
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function blogviews() {
        return $this->hasMany(BlogView::class, 'blog_id');
    }

}