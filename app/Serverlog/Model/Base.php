<?php

declare(strict_types=1);

namespace App\Serverlog\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * App
 */
class Base extends Model
{
    public function scopeWheres($query, array $columns)
    {
        if (empty($columns)) {
            return $query;
        }
        
        foreach ($columns as $column) {
            if (count($column) == 1) {
                $query->where(DB::raw($column[0]));
            } elseif (count($column) == 2) {
                $query->where($column[0], $column[1]);
            } elseif (count($column) == 3) {
                if ($column[1] == 'in') {
                    $query->whereIn($column[0], $column[2]);
                } elseif ($column[1] == 'notin') {
                    $query->whereNotIn($column[0], $column[2]);
                } else {
                    $query->where($column[0], $column[1], $column[2]);
                }
            }
        }
        
        return $query;
    }
    
    public function scopeOrWheres($query, array $columns)
    {
        if (empty($columns)) {
            return $query;
        }
        
        foreach ($columns as $column) {
            if (count($column) == 1) {
                $query->orWhere(DB::raw($column[0]));
            } elseif (count($column) == 2) {
                $query->orWhere($column[0], $column[1]);
            } elseif (count($column) == 3) {
                if ($column[1] == 'in') {
                    $query->orWhereIn($column[0], $column[2]);
                } elseif ($column[1] == 'notin') {
                    $query->orWhereNotIn($column[0], $column[2]);
                } else {
                    $query->orWhere($column[0], $column[1], $column[2]);
                }
            }
        }
        
        return $query;
    }
    
}
