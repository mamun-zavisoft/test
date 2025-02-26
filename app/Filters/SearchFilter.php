<?php

namespace App\Filters;

class SearchFilter
{
    public function handle($products, $next)
    {
        $search = request()->input('search');
        if ($search != null) {
            return $next($products)->where('name', 'LIKE', '%'.$search.'%');
        }

        return $next($products);
    }
}
