<?php

namespace App\Models;

use App\Core\Helpers;

class TaskModel extends Model
{
    public const TABLE_NAME = 'tasker_tasks';

    /** @var int */
    protected $id;

    /** @var string */
    protected $username;

    /** @var string */
    protected $email;

    /** @var bool */
    protected $status;

    /** @var string */
    protected $description;

    /** @var string */
    protected $created_at;

    /** @var string */
    protected $updated_at;

    /**
     * @return array
     */
    public function getOrderableColumns(): array
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
    public function getSelectableColumns(): array
    {
        return [
            'id',
            'username',
            'email',
            'status',
            'description',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return array
     */
    public function getDateColumns(): array
    {
        return [
            'created_at',
            'updated_at',
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
        $orderable = $this->getOrderableColumns();
        $selectable = $this->getSelectableColumns();
        $dateColumns = $this->getDateColumns();
        $parsedQueryString = Helpers::parseQueryString();
        $orderby = !empty($parsedQueryString['orderby']) ? Helpers::clean($parsedQueryString['orderby']) : '';
        $order = !empty($parsedQueryString['order']) ? Helpers::clean($parsedQueryString['order']) : '';

        foreach ($selectable as $columnName) {
            // Skip date name columns
            if (\in_array($columnName, $dateColumns, true)) {
                continue;
            }

            $col = [];
            $htmlClasses = [];
            $orderbyUri = '';

            // If column is orderable, calculate URI for ordering by this column
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
        $orderable = $this->getOrderableColumns();
        $selectable = $this->getSelectableColumns();

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
            if ($stmn->rowCount()){
                $rows = $stmn->fetchAll();
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

        $totalRows = $this->getTotalTasks();

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
    public function getTotalTasks(): int
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

    /**
     * @param int $id
     *
     * @return array
     */
    public function getById(int $id): self
    {
        $selectable = $this->getSelectableColumns();

        $sql = sprintf(
            'SELECT %2$s FROM `%1$s` WHERE `id`=:id LIMIT 1',
            self::TABLE_NAME,
            '`' . implode('`, `', $selectable) . '`'
        );

        try {
            $stmn = $this->db->prepare($sql);
            $stmn->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmn->execute();
            $row = $stmn->fetch();
        } catch (\PDOException $e) {
            //echo $e->getMessage();
        }

        $row = $row ?: [];

        return $this->setFields($row);
    }

    /**
     * @param array $data
     *
     * @return TaskModel
     */
    public function save(array $data): self
    {
        $isCreated = false;
        $colName = [];
        $colParam = [];
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
        $updatedAt = Helpers::isAdminAuth() ? $createdAt : null;

        // Build sql query string
        foreach ($data as $key => $val) {
            $colName[] = '`' . $key . '`';
            $colParam[] = ':' . $key;
        }
        $colName[] = 'created_at';
        $colParam[] = ':created_at';
        $colName[] = 'updated_at';
        $colParam[] = ':updated_at';

        $sql = sprintf(
            'INSERT INTO `%1$s` (%2$s) VALUES (%3$s)',
            self::TABLE_NAME,
            implode(', ', $colName),
            implode(', ', $colParam)
        );

        // Prepare and bind params and execute SQL query
        try {
            $stmn = $this->db->prepare($sql);
            $stmn->bindParam(':created_at', $createdAt);
            $stmn->bindParam(':updated_at', $updatedAt);
            foreach ($data as $key => $val) {
                // $$key - dynamic var name for column value;
                // cause bindParam method take second param by reference;
                $$key = $val;
                $stmn->bindParam(':' . $key, $$key);
            }
            $isCreated = $stmn->execute();
        } catch (\PDOException $e) {
            //echo $e->getMessage();
        }

        return $this->getById($isCreated ? $this->db->lastInsertId() : 0);
    }

    /**
     * @param array     $data
     * @param TaskModel $task
     *
     * @return TaskModel|null
     */
    public function update(array $data, TaskModel $task): ?self
    {
        $isUpdated = false;
        $taskId = $task->getId();
        $cols = [];
        $updatedAt = null;

        // Set updated_at only if Admin edit description of task
        if (!empty($data['description'])
            && $task->getDescription() !== $data['description']
            && Helpers::isAdminAuth()
        ) {
            $updatedAt = (new \DateTime())->format('Y-m-d H:i:s');
        }

        // To Ensure dont update ID
        unset($data['id']);

        // Build sql query string
        foreach ($data as $key => $val) {
            $cols[] = '`' . $key . '`=' . ':' . $key;
        }
        $cols[] = '`updated_at`=:updated_at';

        $sql = sprintf(
            'UPDATE `%1$s` SET %2$s WHERE `id`=:id',
            self::TABLE_NAME,
            implode(', ', $cols)
        );

        // Prepare and bind params and execute SQL query
        try {
            $stmn = $this->db->prepare($sql);
            $stmn->bindParam(':id', $taskId);
            $stmn->bindParam(':updated_at', $updatedAt);
            foreach ($data as $key => $val) {
                // $$key - dynamic var name for column value;
                // cause bindParam method take second param by reference;
                $$key = $val;
                $stmn->bindParam(':' . $key, $$key);
            }
            $isUpdated = $stmn->execute();
        } catch (\PDOException $e) {
            //echo $e->getMessage();
        }

        return $isUpdated ? $this->getById($taskId) : null;
    }

    /**
     * @param array $formData The form data
     *
     * @return array $data The validity form data
     */
    public function validateTaskForm($formData): array
    {
        $errorsTotal = 0;
        $checkedData = [
            'formData' => [
                'username' => false,
                'email' => false,
                'status' => false,
                'description' => false,
            ],
            'formErrors' => [
                'username' => false,
                'email' => false,
                'status' => false,
                'description' => false,
            ],
            'isValidForm' => null,
            'isSubmitForm' => false,
            'errorMessage' => [],
        ];

        // Check If POST request and Form submit
        if (!empty($formData['submit']) && Helpers::isRequestMethod('POST')) {
            unset($formData['submit']);
            // Sanitize POST form data
            $formData = $this->sanitizeForm($formData);

            if (empty($formData['username'])
                || !preg_match('/.{3,60}/', $formData['username'])
            ) {
                ++$errorsTotal;
                $checkedData['formErrors']['username'] = 'Should be min 3 and max 60 characters!';
            }

            if (empty($formData['email'])
                || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)
            ) {
                ++$errorsTotal;
                $checkedData['formErrors']['email'] = 'Should be valid email!';
            }

            if (empty($formData['description'])) {
                ++$errorsTotal;
                $checkedData['formErrors']['description'] = 'Should be filled!';
            }

            $formData['status'] = empty($formData['status']) ? 0 : 1;

            $checkedData['formData'] = $formData;
            $checkedData['isSubmitForm'] = true;
            $checkedData['isValidForm'] = ($errorsTotal === 0);

            if (!$checkedData['isValidForm']) {
                $checkedData['errorMessage'][] = 'Error. Please correct fill some form fields and try again.';
            }
        }

        $checkedData['isValidForm'] = $checkedData['isValidForm'] ?? true;

        return $checkedData;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->getId(),
            'username'    => $this->getUsername(),
            'email'       => $this->getEmail(),
            'status'      => $this->getStatus(),
            'description' => $this->getDescription(),
            'createAt'    => $this->getCreatedAt(),
            'updatedAt'   => $this->getUsername(),
        ];
    }

    /**
     * Getter $id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter $username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Setter $username
     *
     * @param string $username
     *
     * @return TaskModel
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Getter $email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Setter $email
     *
     * @param string $email
     *
     * @return TaskModel
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter $status
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * Setter $status
     *
     * @param bool $status
     *
     * @return TaskModel
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Getter $description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Setter $description
     *
     * @param string $description
     *
     * @return TaskModel
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter $created_at
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Setter $created_at
     *
     * @param string $created_at
     *
     * @return TaskModel
     */
    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Getter $updated_at
     *
     * @return string
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * Setter $updated_at
     *
     * @param string $updated_at
     *
     * @return TaskModel
     */
    public function setUpdatedAt(string $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
