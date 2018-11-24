<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\InsertStatementBuilder $statementBuilder
 */
class InsertQueryBuilder extends QueryBuilder
{
    /**
     * @var string $into
     */
    protected $into = '';

    /**
     * @var array $rows
     */
    protected $rows = [];

    /**
     * Sets table name to insert data into.
     *
     * @param string $table
     * @return InsertQueryBuilder
     */
    public function into(string $table): InsertQueryBuilder
    {
        $this->into = $table;
        return $this;
    }

    /**
     * Inserts new row into database and returns insert-id.
     *
     * @param array $data
     * @return int
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
     */
    public function row(array $data): int
    {
        array_push($this->rows, $data);
        $pdoStatement = $this->provideStatement();
        $this->execute($pdoStatement);
        return $this->getLastInsertId();
    }

    /**
     * Inserts multiple rows into database.
     *
     * @param array $data
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
     */
    public function rows(array $data): void
    {
        $this->rows = $data;
        $pdoStatement = $this->provideStatement();
        $this->execute($pdoStatement);
    }

    /**
     * Builds the SQL statement from all attributes previously set.
     *
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->addInto($this->into);
        $this->statementBuilder->addRows($this->rows);
        return $this->statementBuilder->getStatement();
    }

    /**
     * Fetches last insert-id.
     *
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int) $this->connection->lastInsertId();
    }
}