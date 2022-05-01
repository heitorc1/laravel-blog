<?php

namespace App\Support\Repository\Eloquent;

use Closure;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    /**
     * Local model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Closure
     */
    protected $scopeQuery = null;

    protected $perPage = 10;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @throws \App\Support\Repository\Eloquent\RepositoryException
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Creates a Model object with the $data information.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function factory(array $data = []): Model
    {
        return $this->model->newQuery()->getModel()->newInstance()->fill($data);
    }

    /**
     * Query Scope.
     *
     * @param \Closure $scope
     *
     * @return $this
     */
    public function scopeQuery(Closure $scope): object
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Reset Query Scope.
     *
     * @return $this
     */
    public function resetScope(): object
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query.
     *
     * @return $this
     */
    protected function applyScope(): object
    {
        if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
            $callback = $this->scopeQuery;
            $this->model = $callback($this->model);
        }

        return $this;
    }

    /**
     * Get all of the models from the database.
     * Execute the query as a "select" statement.
     *
     * @param array|mixed $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all(array $columns = ['*']): Collection
    {
        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetScope();

        return $results;
    }

    /**
     * Get the first item from the collection.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first(array $columns = ['*']): Model
    {
        $this->applyScope();

        return $this->model->first($columns);
    }

    /**
     * Find data by field and value.
     *
     * @param string $field
     * @param string $value
     * @param string $comparator
     * @param array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByField(
        string $field,
        string $value,
        string $comparator = '=',
        array $columns = ['*']
    ): Collection {
        $this->applyScope();

        return $this->model->where($field, $comparator, $value)->get($columns);
    }

    /**
     * Find data by multiple fields.
     *
     * @param array $where
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(
        array $where,
        array $columns = ['*']
    ): Collection {
        $this->applyScope();
        $this->applyConditions($where);

        return $this->model->get($columns);
    }

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param int    $perPage
     * @param array  $columns
     * @param string $method
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Pagination\AbstractPaginator
     */
    public function paginate(
        int $perPage = 10,
        array $columns = ['*'],
        string $method = 'paginate'
    ): LengthAwarePaginator {
        $this->applyScope();

        $results = $this->model->{$method}($perPage, $columns);

        $results->appends(app('request')->query());

        return $results;
    }

    /**
     * Save a new entity in repository.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes): Model
    {
        $model = $this->factory($attributes);

        $model->saveOrFail();

        return $model;
    }

    /**
     * Update a entity in repository by id.
     *
     * @param array $attributes
     * @param int   $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(
        array $attributes,
        int $id
    ): Model {
        $this->applyScope();

        $model = $this->model->findOrFail($id);
        $model->fill($attributes);

        $model->saveOrFail();

        return $model;
    }

    public function updateDB(
        $table,
        $where,
        $update
    ) {
        return DB::table($table)
            ->where($where)
            ->update($update);
    }

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollback();
    }

    public function enableDB()
    {
        DB::enableQueryLog();
    }

    public function getDB()
    {
        print_r(DB::getQueryLog());
        die();
    }

    /**
     * Delete a entity in repository by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(int $id): Model
    {
        $this->applyScope();

        $model = $this->findOrFail($id);
        $originalModel = clone $model;

        /** @return bool|null */
        $model->delete();

        // event(new EventRepositoryDeleted($this, $originalModel));

        return $originalModel;
    }

    /**
     * Delete the model from the database.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool|null
     */
    public function deleteModel(Model $model): ?bool
    {
        return $model->delete();
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return bool|null
     */
    public function deleteWhere(array $where): ?bool
    {
        $this->applyScope();

        $this->applyConditions($where);

        $deleted = $this->model->delete();

        return $deleted;
    }

    /**
     * Set hidden fields.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function hidden(array $fields): object
    {
        $this->model->setHidden($fields);

        return $this;
    }

    /**
     * Set visible fields.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function visible(array $fields): object
    {
        $this->model->setVisible($fields);

        return $this;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     *
     * @return void
     */
    protected function applyConditions(array $where): void
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    /**
     * Forward all method calls to \Illuminate\Database\Eloquent\Model.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->model, $method], $parameters);
    }
}
