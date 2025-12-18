<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistories extends Model
{
    use HasFactory;

    protected $table = 'history';


    protected $fillable = [
        'user_id',
        'search_query',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
