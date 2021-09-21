<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait CollectionsTrait {
    public function paginateCollections($items, $perPage = 15, $page = null)
    {
        $pageName = 'page';
        $page     = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage($pageName) ?: 1);
        $items    = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path'     => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }
}
