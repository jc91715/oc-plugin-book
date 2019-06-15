<?php namespace Jc91715\Book\Models;

use October\Rain\Database\Pivot;

class UserChapterPivotModel extends Pivot
{




    protected $casts = [
        'extra'=>'array'
    ];



}
