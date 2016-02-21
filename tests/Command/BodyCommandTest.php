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

use Rvdv\Nntp\Command\BodyCommand;
use Rvdv\Nntp\Response\Response;

/**
 * BodyCommandTest.
 *
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class BodyCommandTest extends CommandTest
{
    public function testItExpectsMultilineResponses()
    {
        $command = $this->createCommandInstance();
        $this->assertTrue($command->isMultiLine());
    }

    public function testItNotExpectsCompressedResponses()
    {
        $command = $this->createCommandInstance();
        $this->assertFalse($command->isCompressed());
    }

    public function testItHasDefaultResult()
    {
        $command = $this->createCommandInstance();
        $this->assertEmpty($command->getResult());
    }

    public function testItReturnsStringWhenExecuting()
    {
        $command = $this->createCommandInstance();
        $this->assertEquals('BODY 12345', $command->execute());
    }

    public function testItReceivesAResultWhenBodyFollowsResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\MultiLineResponse')
            ->disableOriginalConstructor()
            ->getMock();

        $lines = \SplFixedArray::fromArray(['Lorem ipsum dolor sit amet, ', 'consectetur adipiscing elit. ', 'Sed volutpat sit amet leo sit amet sagittis.']);

        $response->expects($this->once())
            ->method('getLines')
            ->will($this->returnValue($lines));

        $command->onBodyFollows($response);

        $result = $command->getResult();

        $this->assertEquals(implode("\r\n", $lines->toArray()), $result);
    }

    public function testItErrorsWhenGroupNotSelectedResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        try {
            $command->onNoNewsGroupCurrentSelected($response);
            $this->fail('->onNoNewsGroupCurrentSelected() throws a Rvdv\Nntp\Exception\RuntimeException because the server indicated a newsgroup has not been selected');
        } catch (\Exception $e) {
            $this->assertInstanceof('Rvdv\Nntp\Exception\RuntimeException', $e, '->onNoNewsGroupCurrentSelected() throws a Rvdv\Nntp\Exception\RuntimeException because the server indicated a newsgroup has not been selected');
        }
    }

    public function testItErrorsWhenNoSuchArticleNumberResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        try {
            $command->onNoSuchArticleNumber($response);
            $this->fail('->onNoSuchArticleNumber() throws a Rvdv\Nntp\Exception\RuntimeException because the server indicated the article number does not exist');
        } catch (\Exception $e) {
            $this->assertInstanceof('Rvdv\Nntp\Exception\RuntimeException', $e, '->onNoSuchArticleNumber() throws a Rvdv\Nntp\Exception\RuntimeException because the server indicated the article number does not exist');
        }
    }

    public function testItErrorsWhenNoSuchArticleIdResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\Response')
            ->disableOriginalConstructor()
            ->getMock();

        try {
            $command->onNoSuchArticleId($response);
            $this->fail('->onNoSuchArticleId() throws a Rvdv\Nntp\Exception\RuntimeException because the server indicated the article id does not exist');
        } catch (\Exception $e) {
            $this->assertInstanceof('Rvdv\Nntp\Exception\RuntimeException', $e, '->onNoSuchArticleId() throws a Rvdv\Nntp\Exception\RuntimeException because the server indicated the article id does not exist');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createCommandInstance()
    {
        return new BodyCommand('12345');
    }

    /**
     * {@inheritdoc}
     */
    protected function getRFCResponseCodes()
    {
        return [
            Response::BODY_FOLLOWS,
            Response::NO_NEWSGROUP_CURRENT_SELECTED,
            Response::NO_SUCH_ARTICLE_NUMBER,
            Response::NO_SUCH_ARTICLE_ID,
        ];
    }
}
