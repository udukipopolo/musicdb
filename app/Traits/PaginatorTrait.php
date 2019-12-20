<?php
namespace App\Traits;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait PaginatorTrait
{
    private function paginate(Builder $builder, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null) : LengthAwarePaginator
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $builder->getModel()->getPerPage();

        $results = ($total = $builder->toBase()->getCountForPagination($columns))
            ? $builder->forPage($page, $perPage)->get($columns)
            : $builder->getModel()->newCollection();

        return Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'options' => [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ],
        ]);
    }

}
