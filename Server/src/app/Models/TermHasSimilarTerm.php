<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TermHasSimilarTerm extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'term_id',
        'similar_term_id',
    ];

    public function term()
    {
        return $this->hasOne(Term::class, 'id', 'similar_term_id');
    }
}
