<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
    ];

    public function scopeFilter($query, $request)
    {
        $search = $request->input('query');
        
        $query->when($search, function($q) use ($search) {
            $q->where('id', $search)
                ->orWhere('question', 'LIKE', "%$search%");
        });

        return $query;
    }
}
