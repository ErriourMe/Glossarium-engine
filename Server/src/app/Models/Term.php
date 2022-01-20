<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Term extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'author_id',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->search_fields = [
            'id',
            'title',
            'description',
        ];
        $this->order_fields = [
            'id',
            'title',
            'description',
        ];
    }

    public function similarTerms()
    {
        return $this->hasMany(TermHasSimilarTerm::class, 'term_id')->with('term');
    }
}
