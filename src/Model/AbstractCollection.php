<?php

declare(strict_types=1);

namespace Vstore\Router\Model;

use Vstore\Router\Model\AbstractModel;
use Vstore\Router\Model\AbstractRepository;

/**
 * Class AbstractCollection
 */
abstract class AbstractCollection
{
    /**
     * @var array
     */
    protected array $items = [];

    /**
     * @var string
     */
    protected  string $filterQuery;

    /**
     * @var string
     */
    private string  $model;

    /**
     * @var AbstractRepository|string
     */
    private AbstractRepository|string $repository;

    /**
     * @var array
     */
    private array $ids = [];

    /**
     * @param string $model
     * @param \Vstore\Router\Model\AbstractRepository|string $repository
     * @return void
     */
    protected function _init(
        string $model,
        AbstractRepository | string $repository
    ): void {
        $this->model = $model;
        $this->repository = $repository;
    }

    /**
     * @param string $query
     * @return $this
     */
    public  function addFilterQuery(string $query): static
    {
        $this->filterQuery = $query;

        return $this;
    }

    /**
     * @return $this
     */
    public function deleteFilterQuery(): static
    {
        unset($this->filterQuery);

        return $this;
    }

    /**
     * Run query function
     *
     * @return $this
     */
    public function load(): static
    {
        if ($this->ids) {
            return $this;
        }

        $query = "SELECT * FROM " . $this->model::TABLE_NAME;

        if (isset($this->filterQuery)) {
            $query .= " WHERE " . $this->filterQuery;
        }

        $repository = $this->getRepository();
        $fetchResult = $repository
            ->getConnect()
            ->query($query)
            ->fetchAll(\PDO::FETCH_COLUMN, (int)$this->model::PRIMARY_KEY);
        $this->ids = $fetchResult;
        // TODO implement getList function inside the repository and run it here
        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        if (!$this->items) {
            $this->load();

            foreach ($this->ids as $id) {
                $this->items[] = $this->getRepository()->getById($id);
            }
        }

        return $this->items;
    }

    /**
     * @return mixed|null
     */
    public function getFirstItem(): mixed
    {
        if (!$this->items) {
            $this->getItems();

            return array_shift($this->items);
        }

        return array_shift($this->items);
    }

    /**
     * @return \Vstore\Router\Model\AbstractRepository
     */
    private function getRepository(): AbstractRepository
    {
        if (!$this->repository instanceof AbstractRepository) {
            if (is_string($this->repository)) {
                if (class_exists($this->repository)) {
                    $this->repository = new $this->repository();
                } else {
                    throw new \RuntimeException("Class {$this->repository} does not exist");
                }
            } else {
                throw new \RuntimeException(
                    "Please set model and repository instance through _init method in __construct"
                );
            }
        }

        return $this->repository;
    }
}
