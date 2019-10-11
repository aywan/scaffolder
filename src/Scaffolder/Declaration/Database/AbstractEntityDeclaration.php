<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\Database;

use Doctrine\Common\Inflector\Inflector;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;
use Spiral\Reactor\Partial\Property;

abstract class AbstractEntityDeclaration extends ClassDeclaration implements DependedInterface
{
    /** @var string|null */
    protected $role;

    /** @var string|null */
    protected $mapper;

    /** @var string|null */
    protected $repository;

    /** @var string|null */
    protected $table;

    /** @var string|null */
    protected $database;

    /** @var string|null */
    protected $inflection;

    /**
     * @param string|null $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @param string|null $mapper
     */
    public function setMapper(string $mapper): void
    {
        $this->mapper = $mapper;
    }

    /**
     * @param string $repository
     */
    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @param string|null $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * @param string $database
     */
    public function setDatabase(string $database): void
    {
        $this->database = $database;
    }

    /**
     * @param string $inflection
     */
    public function setInflection(string $inflection): void
    {
        $this->inflection = $inflection;
    }

    /**
     * Add field.
     *
     * @param string $name
     * @param string $accessibility
     * @param string $type
     * @return Property
     */
    public function addField(string $name, string $accessibility, string $type): Property
    {
        $property = $this->property($name);
        $property->setComment("@var {$this->variableType($type)}");
        if ($accessibility) {
            $property->setAccess($accessibility);
        }

        if ($property->getAccess() !== self::ACCESS_PUBLIC) {
            $this->declareAccessors($name, $type);
        }

        return $property;
    }

    abstract public function declareSchema(): void;

    /**
     * @param string $type
     * @return bool
     */
    protected function isNullableType(string $type): bool
    {
        return strpos($type, '?') === 0;
    }

    /**
     * @param string $type
     * @return string
     */
    private function variableType(string $type): string
    {
        return $this->isNullableType($type) ? (substr($type, 1) . '|null') : $type;
    }

    /**
     * @param string $field
     * @param string $type
     */
    private function declareAccessors(string $field, string $type): void
    {
        $setter = $this->method('set' . Inflector::classify($field));
        $setter->setPublic();
        $setter->parameter('value')->setType($type);
        $setter->setSource("\$this->$field = \$value;");

        $getter = $this->method('get' . Inflector::classify($field));
        $getter->setPublic();
        $getter->setSource("return \$this->$field;");
    }
}
