<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     *
     * @param  \App\Models\Category  $Category
     * @return void
     */
    public function creating(Category $category)
    {
        $category->url = Str::slug($category->title);
    }

    /**
     * Handle the Category "updating" event.
     *
     * @param  \App\Models\Category  $Category
     * @return void
     */
    public function updating(Category $category)
    {
        $category->url = Str::slug($category->title);
    }
}
