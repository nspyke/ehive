<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
