<?php


namespace Uginroot\PhpSet\Traits;


use Generator;

trait GeneratorTrait
{
    /**
     * @param mixed $values
     * @return Generator
     */
    final private static function valueGenerator($values)
    {
        if (is_iterable($values)) {
            foreach ($values as $value) {
                foreach (static::valueGenerator($value) as $subValue) {
                    yield $subValue;
                }
            }
        } elseif ($values instanceof static) {
            foreach ($values->getValues() as $value) {
                yield $value;
            }
        } else {
            yield $values;
        }
    }

    /**
     * @param mixed $values
     * @return array|mixed[]
     */
    final private static function valueExtractor($values): array
    {
        $valuesResult = [];
        foreach (static::valueGenerator($values) as $value) {
            $valuesResult[] = $value;
        }
        $valuesResult = array_unique($valuesResult);
        return $valuesResult;
    }

    /**
     * @param $names
     * @return Generator
     */
    final private static function nameGenerator($names){
        if(is_iterable($names)){
            foreach ($names as $name){
                foreach (static::nameGenerator($name) as $subName){
                    yield $subName;
                }
            }
        } else {
            yield $names;
        }
    }
}