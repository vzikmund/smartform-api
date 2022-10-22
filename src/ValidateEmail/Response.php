<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateEmail;


use Vzikmund\SmartformApi\BaseResponse;

/**
 * @property-read string|null $errorMessage
 * @property-read string      $result
 * @property-read array       $resultFlags
 * @property-read string|null $hint
 */
final class Response extends BaseResponse
{

    public const
        resultExists = "EXISTS",
        resultNotExists = "NOT_EXISTS",
        resultUnknown = "UNKNOWN";

    /**
     * Emailová chránka existuje
     * @return bool
     */
    public function exists() : bool
    {
        return $this->result === self::resultExists;
    }

    /**
     * @return bool
     */
    public function isUnknown() : bool
    {
        return $this->result === self::resultUnknown;
    }


    /**
     * Zkontrolovat, zda result flag existuje
     *
     * @param string $val
     *
     * @return bool
     */
    private function isInResultFlags(string $val) : bool
    {
        return in_array($val, $this->resultFlags);
    }

    /**
     * Doručená pošta je plná, takže e-maily nelze doručit.
     * @return bool
     */
    public function isFullInbox() : bool
    {
        return $this->isInResultFlags("FULL_INBOX");
    }

    /**
     * Špatná syntaxe e-mailové adresy.
     * @return bool
     */
    public function isBadSyntax() : bool
    {
        return $this->isInResultFlags("BAD_SYNTAX");
    }

    /**
     * Nesprávná nebo neexistující doména.
     * @return bool
     */
    public function isBadDomain() : bool
    {
        return $this->isInResultFlags("BAD_DOMAIN");
    }

    /**
     * Uživatel (místní část e-mailové adresy) neexistuje na poštovním serveru.
     * @return bool
     */
    public function isMailboxNotFound() : bool
    {
        return $this->isInResultFlags("MAILBOX_NOT_FOUND");
    }

    /**
     * Doména přijímá všechny e-maily. Nelze určit, jestli zadaná schránka existuje.
     * @return bool
     */
    public function isAcceptAllPolicy() : bool
    {
        return $this->isInResultFlags("ACCEPT_ALL_POLICY");
    }

    /**
     * Dočasná chyba, e-mailová adresa může být znovu ověřena později.
     * @return bool
     */
    public function isTemporary() : bool
    {
        return $this->isInResultFlags("TEMPORARY");
    }


}