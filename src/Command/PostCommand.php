<?php

/*
 * This file is part of the NNTP library.
 *
 * (c) Robin van der Vleuten <robinvdvleuten@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rvdv\Nntp\Command;

use Rvdv\Nntp\Exception\RuntimeException;
use Rvdv\Nntp\Response\Response;

/**
 * PostCommand
 *
 * @author thebandit
 */
class PostCommand extends Command implements CommandInterface
{
    /**
    * Constructor.
    */
   public function __construct()
   {
       parent::__construct(array());
   }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedResponseCodes()
    {
        return array(
            Response::SEND_ARTICLE => 'onSendArticle',
            Response::POSTING_NOT_PERMITTED => 'onPostingNotPermitted',
        );
    }

    public function onSendArticle(Response $response)
    {
        return;
    }

    public function onPostingNotPermitted(Response $response)
    {
        throw new RuntimeException('Posting not permitted.', $response->getStatusCode());
    }
}
