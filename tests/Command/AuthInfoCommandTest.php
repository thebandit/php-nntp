<?php

/*
 * This file is part of the NNTP library.
 *
 * (c) Robin van der Vleuten <robinvdvleuten@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rvdv\Nntp\Tests\Command;

use Rvdv\Nntp\Command\AuthInfoCommand;
use Rvdv\Nntp\Response\Response;

/**
 * AuthInfoCommandTest.
 *
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class AuthInfoCommandTest extends CommandTest
{
    public function testItNotExpectsMultilineResponses()
    {
        $command = $this->createCommandInstance();
        $this->assertFalse($command->isMultiLine());
    }

    public function testItNotExpectsCompressedResponses()
    {
        $command = $this->createCommandInstance();
        $this->assertFalse($command->isCompressed());
    }

    public function testItNotHasDefaultResult()
    {
        $command = $this->createCommandInstance();
        $this->assertNull($command->getResult());
    }

    public function testItReturnsStringWhenExecuting()
    {
        $command = $this->createCommandInstance(AuthInfoCommand::AUTHINFO_USER, 'user');
        $this->assertEquals('AUTHINFO USER user', $command->execute());

        $command = $this->createCommandInstance(AuthInfoCommand::AUTHINFO_PASS, 'pass');
        $this->assertEquals('AUTHINFO PASS pass', $command->execute());
    }

    public function testItNotReceivesAResultWhenAuthenticatedAcceptedResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $command->onAuthenticationAccepted($response);

        $this->assertNull($command->getResult());
    }

    public function testItNotReceivesAResultWhenPasswordRequiredResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $command->onPasswordRequired($response);

        $this->assertNull($command->getResult());
    }

    public function testItErrorsWhenAuthenticationRejectedResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        try {
            $command->onAuthenticationRejected($response);
            $this->fail('->onAuthenticationRejected() throws a Rvdv\Nntp\Exception\RuntimeException because the authentication is rejected');
        } catch (\Exception $e) {
            $this->assertInstanceof('Rvdv\Nntp\Exception\RuntimeException', $e, '->onAuthenticationRejected() throws a Rvdv\Nntp\Exception\RuntimeException because the authentication is rejected');
        }
    }

    public function testItErrorsWhenAuthenticationOutOfSequenceResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        try {
            $command->onAuthenticationOutOfSequence($response);
            $this->fail('->onAuthenticationOutOfSequence() throws a Rvdv\Nntp\Exception\RuntimeException because the authentication is out of sequence');
        } catch (\Exception $e) {
            $this->assertInstanceof('Rvdv\Nntp\Exception\RuntimeException', $e, '->onAuthenticationOutOfSequence() throws a Rvdv\Nntp\Exception\RuntimeException because the authentication is out of sequence');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createCommandInstance()
    {
        $args = func_get_args();
        if (empty($args)) {
            $args = [AuthInfoCommand::AUTHINFO_USER, 'user'];
        }

        return new AuthInfoCommand($args[0], $args[1]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRFCResponseCodes()
    {
        return [
            Response::AUTHENTICATION_ACCEPTED,
            Response::PASSWORD_REQUIRED,
            Response::AUTHENTICATION_REJECTED,
            Response::AUTHENTICATION_OUTOFSEQUENCE,
        ];
    }
}
