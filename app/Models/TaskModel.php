<?php

namespace App\Models;

use App\Core\Helpers;

class TaskModel extends Model
{
    public const TABLE_NAME = 'tasks';

    /**
     * @return array
     */
    public function getColumnsNameForOrderBy(): array
    {
        return [
            'id',
            'username',
            'email',
            'status',
        ];
    }

    /**
     * @return array
     */
    public function getColumnsNameForSelected(): array
    {
        return [
            'id',
            'username',
            'email',
            'status',
            'description',
        ];
    }

    /**
     * @return array
     */
    public function getDefaultGETParams(): array
    {
        return [
            'orderby' => 'id',
            'order'   => 'asc',
            'page'    => 1,
            'limit'   => PER_PAGE,
        ];
    }

    /**
     * @return array $cols
     */
    public function getColumnsMeta(): array
    {
        $cols = [];
        $orderable = $this->getColumnsNameForOrderBy();
        $selectable = $this->getColumnsNameForSelected();
        $parsedQueryString = Helpers::parseQueryString();
        $orderby = !empty($parsedQueryString['orderby']) ? Helpers::clean($parsedQueryString['orderby']) : '';
        $order = !empty($parsedQueryString['order']) ? Helpers::clean($parsedQueryString['order']) : '';

        foreach ($selectable as $columnName) {
            $col = [];
            $htmlClasses = [];
            $orderbyUri = '';

            if (\in_array(strtolower($columnName), $orderable, true)) {
                $htmlClasses[] = 'ordering';
                $htmlClasses[] = $orderby === $columnName ? 'active' : '';
                $htmlClasses[] = $order === 'asc' ? 'asc' : 'desc';
                $parsedQueryString['order'] = !$order || $order === 'asc' ? 'desc' : 'asc';
                $parsedQueryString['orderby'] = $columnName;
                $queryString = array_merge($this->getDefaultGETParams(), $parsedQueryString);
                $orderbyUri = Helpers::path('task/table', $queryString);
            }

            $col['orderbyUri'] = $orderbyUri;
            $col['htmlClasses'] = implode(' ',$htmlClasses);
            $col['columnName'] = $columnName;
            $cols[] = $col;
        }

        return $cols;
    }

    /**
     * @param array $args
     *
     * @return array $rows
     */
    public function getTasks(array $args = []): array
    {
        $rows = [];
        $orderable = $this->getColumnsNameForOrderBy();
        $selectable = $this->getColumnsNameForSelected();

        //Default Args
        $argsDefault = $this->getDefaultGETParams();
        $args = array_merge($argsDefault, $args);

        // Check orderby params
        $args['orderby'] = strtolower(Helpers::clean($args['orderby']));
        $args['order'] = strtolower(Helpers::clean($args['order']));
        $args['page'] = Helpers::clean($args['page'], 'int');
        $args['limit'] = Helpers::clean($args['limit'], 'int');

        $args['orderby'] = \in_array($args['orderby'], $orderable, true) ? $args['orderby'] : $argsDefault['orderby'];
        $args['order'] = \in_array($args['order'], ['asc', 'desc']) ? $args['order'] : $argsDefault['order'];
        $args['limit'] = $args['limit'] < 0 ? $argsDefault['limit'] : $args['limit'];
        $args['page'] = $args['page'] < 1 ? $argsDefault['page'] : $args['page'];
        $offset = $args['limit'] * ($args['page'] - 1);

        $sql = sprintf(
            'SELECT %2$s FROM `%1$s` ORDER BY `%3$s` %4$s LIMIT %5$d OFFSET %6$d',
                self::TABLE_NAME,
                '`' . implode('`, `', $selectable) . '`',
                $args['orderby'],
                $args['order'],
                $args['limit'],
                $offset
        );

        // Prepare and execute SQL query
        try {
            $stmn = $this->db->query($sql);
            $stmn->setFetchMode(\PDO::FETCH_ASSOC);
            if ($stmn->rowCount()){
                $rows = $stmn->fetchAll();
            }
        } catch (\PDOException $e) {
            //echo $e->getMessage();
        }

        $totalRows = $this->getTotalTasks();

        $this->db = null;

        $paginationMeta = Helpers::calculatePaginationMeta(
            $totalRows,
            \count($rows),
            $args['page'],
            $args['limit']
        );

        return [
            'args'            => $args,
            'tasks'           => $rows,
            'paginationMeta'  => $paginationMeta,
            'paginationLinks' => $this->getPaginationLinks($paginationMeta, 'task/table'),
        ];
    }

    /**
     * @param array  $paginationMeta
     * @param string $path
     *
     * @return array
     */
    protected function getPaginationLinks(
        array $paginationMeta,
        string $path
    ): array {
        $parsedQueryString = Helpers::parseQueryString();
        $links['previous'] = '';
        $links['next'] = '';
        $links['paged'] = [];

        if ($paginationMeta['previous'] > 0) {
            $parsedQueryString['page'] = $paginationMeta['previous'];
            $links['previous'] = Helpers::path($path, $parsedQueryString);
        }

        if ($paginationMeta['next'] > 0) {
            $parsedQueryString['page'] = $paginationMeta['next'];
            $links['next'] = Helpers::path($path, $parsedQueryString);
        }

        if ($paginationMeta['total'] > 0) {
            for ($i = 1; $i <= $paginationMeta['total']; $i++) {
                $parsedQueryString['page'] = $i;
                $links['paged'][$i]['isActive'] = $paginationMeta['current'] === $i;
                $links['paged'][$i]['value'] = Helpers::path($path, $parsedQueryString);
            }
        }

        return $links;
    }

    /**
     * @return int
     */
    protected function getTotalTasks(): int
    {
        $sql = sprintf(
            'SELECT COUNT(id) as total FROM `%1$s`',
            self::TABLE_NAME
        );

        try {
            $stmn = $this->db->query($sql);
            $total = $stmn->fetch();
        } catch (\PDOException $e) {
            //echo $e->getMessage();
        }

        return !empty($total['total']) ? (int) $total['total'] : 0;
    }
}
