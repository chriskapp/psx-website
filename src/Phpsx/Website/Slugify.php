<?php

namespace Phpsx\Website;

class Slugify
{
    public function slugify($title)
    {
        return strtolower(str_replace(' ', '-', $title));
    }
}
