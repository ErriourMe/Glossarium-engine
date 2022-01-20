<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected array $search_fields;
    protected array $order_fields;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->search_fields = [];
        $this->order_fields = [];
    }

    public static function filters($request)
    {
        $self = new static;
        $query = Self::query();

        $search = $request['search'] ?? '';
        $orders = $request['order'] ?? [];

        if($search) {
            foreach($self->search_fields as $key => $field) {
                if(str_contains($field, '.')) {
                    [$with, $field] = explode('.', $field);
                    if ($key === array_key_first($self->search_fields)) {
                        $query->whereHas($with, function($q) use ($field, $search) {
                            $q->where($field, "like", "%{$search}%");
                        });
                        continue;
                    }
                    $query->orWhereHas($with, function($q) use ($field, $search) {
                        $q->where($field, "like", "%{$search}%");
                    });
                } else {
                    if ($key === array_key_first($self->search_fields)) {
                        $query->where($field, "like", "%{$search}%");
                        continue;
                    }
                    $query->orWhere($field, "like", "%{$search}%");
                }
            }
        }

        if($orders) {
            foreach($orders as $order => $direction) {
                if(!in_array($order, $self->order_fields)) continue;
                $query->orderBy($order, $direction);
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        return $query;
    }
}
