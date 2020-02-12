<?php


namespace Uginroot\PhpSet\Traits;


trait EditObjectTrait
{
    use ObjectTrait;
    use InitTrait;

    /**
     * @param EditObjectTrait $self
     * @param $value
     * @return void
     */
    final private static function addObjectValue(self $self, $value): void
    {
        $self->values[static::getName($value)] = $value;
    }

    /**
     * @param EditObjectTrait $self
     * @param string $name
     * @return void
     */
    final private static function addObjectName(self $self, string $name): void
    {
        $self->values[$name] = static::getValue($name);
    }

    /**
     * @param EditObjectTrait $self
     * @param $value
     * @return void
     */
    final private static function removeObjectValue(self $self, $value): void
    {
        unset($self->values[static::getName($value)]);
    }

    /**
     * @param EditObjectTrait $self
     * @param string $name
     * @return void
     */
    final private static function removeObjectName(self $self, string $name): void
    {
        unset($self->values[$name]);
    }

    /**
     * @param EditObjectTrait $self
     * @param mixed[] $values
     */
    final private static function addObjectValues(self $self, array $values): void
    {
        foreach (static::valueExtractor($values) as $value) {
            static::addObjectValue($self, $value);
        }
    }

    /**
     * @param EditObjectTrait $self
     * @param string[] $names
     */
    final private static function addObjectNames(self $self, array $names): void
    {
        foreach (static::nameGenerator($names) as $name){
            static::addObjectName($self, $name);
        }
    }

    /**
     * @param EditObjectTrait $self
     * @param mixed[] $values
     * @return void
     */
    final private static function setObjectValues(self $self, array $values): void
    {
        $self->values = [];
        static::addObjectValues($self, $values);
    }

    /**
     * @param EditObjectTrait $self
     * @param string[] $names
     */
    final private static function setObjectNames(self $self, array $names): void
    {
        $self->values = [];
        static::addObjectNames($self, $names);
    }

    /**
     * @param EditObjectTrait $self
     * @param mixed[] $values
     */
    final private static function removeObjectValues(self $self, array $values): void
    {
        foreach (static::valueGenerator($values) as $value){
            static::removeObjectValue($self, $value);
        }
    }

    /**
     * @param EditObjectTrait $self
     * @param string[] $names
     */
    final private static function removeObjectNames(self $self, array $names): void
    {
        foreach (static::nameGenerator($names) as $name){
            static::removeObjectName($self, $name);
        }
    }
}