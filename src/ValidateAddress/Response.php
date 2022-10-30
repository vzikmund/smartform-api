<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateAddress;


use Vzikmund\SmartformApi\BaseResponse;

/**
 * @property-read string|null $errorMessage
 */
final class Response extends BaseResponse
{

    public const
        resultTypeHit = "HIT",
        resultTypePartialHit = "PARTIAL_HIT",
        resultTypeNothing = "NOTHING",
        resultTypeMany = "MANY",
        resultTypeTooMany = "TOO_MANY";

    /** @var array */
    private $result;

    /** @var null|Hint */
    private $hint = null;

    public function __construct(array $content)
    {
        parent::__construct($content);
        $this->result = $content[ "result" ];
    }

    /**
     * @return array
     */
    public function getResult() : array
    {
        return $this->result;
    }

    /**
     * Typ výsledku
     * @return string
     */
    public function getResultType() : string
    {
        return $this->result[ "type" ];
    }

    /**
     * Porovnani typu vysledku
     *
     * @param string $type
     *
     * @return bool
     */
    public function isResultType(string $type) : bool
    {
        return $this->getResultType() === mb_strtoupper($type);
    }

    /**
     * Doplnujici informace k validaci
     * @return \Vzikmund\SmartformApi\ValidateAddress\Hint|null
     */
    public function getHint() : ?Hint
    {
        # jiz bylo jednou inicializovano
        if ($this->hint instanceof Hint) {
            return $this->hint;
        }

        $hint = $this->result[ "hint" ];

        # hint neni soucasni odpovedi
        if (!$hint) {
            return null;
        }

        $this->hint = new Hint($hint);

        return $this->hint;
    }

}