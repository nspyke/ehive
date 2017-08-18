<?php

namespace EHive\Tests\Domain\Account;

use EHive\Domain\Account\Account;
use EHive\Domain\Account\AccountsCollection;

class AccountsCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new AccountsCollection(o([
            'totalAccounts' => 1,
            'maxSearchScore' => 10,
            'accounts' => [
                [
                    'accountId' => 1,
                ]
            ],
        ]));

        $this->assertInternalType('array', $obj->accounts);
        $this->assertNotEmpty($obj->accounts);
        $this->assertInstanceOf(Account::class, $obj->accounts[0]);
    }
}
