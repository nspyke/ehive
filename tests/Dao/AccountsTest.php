<?php
namespace EHive\Tests\Dao;

use EHive\Dao\Accounts;
use EHive\Domain\Account\Account;
use EHive\Domain\Account\AccountsCollection;

class AccountsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['accountId' => 123])));

        $dao = new Accounts($this->transport);

        $response = $dao->getAccount(123);
        $this->assertInstanceOf(Account::class, $response);
        $this->assertEquals(123, $response->accountId);
    }

    public function testGetAccountInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['accountId' => 123])));

        $dao = new Accounts($this->transport);

        $response = $dao->getAccountInCommunity(123, 456);
        $this->assertInstanceOf(Account::class, $response);
        $this->assertEquals(123, $response->accountId);
    }

    public function testGetAccountsInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['accountId' => 123])));

        $dao = new Accounts($this->transport);

        $response = $dao->getAccountsInEHive('abc');
        $this->assertInstanceOf(AccountsCollection::class, $response);
    }

    public function testGetAccountsInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['accountId' => 123])));

        $dao = new Accounts($this->transport);

        $response = $dao->getAccountsInCommunity(123, 'abc');
        $this->assertInstanceOf(AccountsCollection::class, $response);
    }
}
