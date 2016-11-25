<?php

/*
 * This file is part of the NNTP library.
 *
 * (c) Robin van der Vleuten <robin@webstronauts.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rvdv\Nntp\Command;

use Rvdv\Nntp\Exception\RuntimeException;
use Rvdv\Nntp\Response\MultiLineResponse;
use Rvdv\Nntp\Response\Response;

/**
 * BodyCommand.
 *
 * @author thebandit
 */
class ArticleCommand extends Command
{
    /**
     * @var string
     */
    private $article;

    /**
     * Constructor.
     *
     * @param string $article The number or msg-id of the article.
     */
    public function __construct($article)
    {
        $this->article = $article;

        parent::__construct([], true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return sprintf('ARTICLE %s', $this->article);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedResponseCodes()
    {
        return [
            Response::ARTICLE_FOLLOWS               => 'onArticleFollows',
            Response::NO_NEWSGROUP_CURRENT_SELECTED => 'onNoNewsGroupCurrentSelected',
            Response::NO_SUCH_ARTICLE_NUMBER        => 'onNoSuchArticleNumber',
            Response::NO_SUCH_ARTICLE_ID            => 'onNoSuchArticleId',
        ];
    }

    public function onArticleFollows(MultiLineResponse $response)
    {
        $lines = $response->getLines();
        $this->result = implode("\r\n", $lines->toArray());
    }

    public function onNoNewsGroupCurrentSelected(Response $response)
    {
        throw new RuntimeException('A group must be selected first before getting an article.', $response->getStatusCode());
    }

    public function onNoSuchArticleNumber(Response $response)
    {
        throw new RuntimeException('No article with that number.', $response->getStatusCode());
    }

    public function onNoSuchArticleId(Response $response)
    {
        throw new RuntimeException('No article with that message-id.', $response->getStatusCode());
    }
}
