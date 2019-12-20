<?php
namespace App\Traits;

use Illuminate\Pagination\Paginator;

trait PaginatorTrait
{
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->toBase()->getCountForPagination($columns))
                                    ? $this->forPage($page, $perPage)->get($columns)
                                    : $this->model->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}
