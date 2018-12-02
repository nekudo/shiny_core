<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Database\QueryBuilder\QueryBuilder;

class QueryBuilderMock extends QueryBuilder
{
    protected $testStatement = '';

    public function setTestStatement(string $statement, array $values): void
    {
        $this->testStatement = $statement;
        foreach ($values as $key => $value) {
            $this->statementBuilder->addBindingValue($key, $value);
        }
    }

    public function execProvideStatement(): \PDOStatement
    {
        return $this->provideStatement();
    }

    public function execPrepareStatement(string $statement, array $values): \PDOStatement
    {
        return $this->prepareStatement($statement, $values);
    }

    protected function buildStatement(): string
    {
        return $this->testStatement;
    }
}