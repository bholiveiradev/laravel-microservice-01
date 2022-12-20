<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'url',
        'email',
        'phone',
        'whatsapp',
        'facebook',
        'instagram',
        'youtube',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCompanies(string $search = '')
    {
        return $this->with('category')->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('name', 'LIKE', "%{$search}%");
                    $query->orWhere('email', $search);
                    $query->orWhere('phone', $search);
                }
            })
            ->paginate();
    }
}
