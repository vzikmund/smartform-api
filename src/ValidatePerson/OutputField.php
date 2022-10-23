<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidatePerson;


/**
 * @property-read string      $fieldType
 * @property-read string      $value
 * @property-read string      $result
 * @property-read string|null $hint
 */
final class OutputField
{

    public const
        resultTypeHit = "HIT",
        resultTypeRepaired = "REPAIRED",
        resultTypeFilledIn = "FILLEDIN",
        resultTypeSwapped = "SWAPPED",
        resultTypeSexMisMatch = "SEX_MISMATCH",
        resultTypeInsufficientData = "INSUFFICIENT_DATA",
        resultTypeMany = "MANY",
        resultTypeNothing = "NOTHING";

    /** @var array */
    private $field;

    public function __construct(array $field)
    {
        $this->field = $field;
    }

    public function __get($property)
    {
        return $this->field[ $property ] ?? null;
    }

    public function isResultHit() : bool
    {
        return $this->isResult(self::resultTypeHit);
    }

    public function isResultRepaired() : bool
    {
        return $this->isResult(self::resultTypeRepaired);
    }

    public function isResultFilledIn() : bool
    {
        return $this->isResult(self::resultTypeFilledIn);
    }

    public function isResultSwapped() : bool
    {
        return $this->isResult(self::resultTypeSwapped);
    }

    public function isResultSexMisMatch() : bool
    {
        return $this->isResult(self::resultTypeSexMisMatch);
    }

    public function isResultInsufficientData() : bool
    {
        return $this->isResult(self::resultTypeInsufficientData);
    }

    public function isResultTypeMany() : bool
    {
        return $this->isResult(self::resultTypeMany);
    }

    public function isResultNothing() : bool
    {
        return $this->isResult(self::resultTypeNothing);
    }


    /**
     * @param string $expected
     *
     * @return bool
     */
    public function isResult(string $expected) : bool
    {
        return $this->result === $expected;
    }


    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->field;
    }

}