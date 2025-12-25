<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    public function doctors(){
        return $this->hasMany(Doctor::class);
    }
}
