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

use Rvdv\Nntp\Response\ResponseInterface;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
abstract class Command implements CommandInterface
{
    /**
     * @var bool
     */
    protected $isMultiLine;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * Constructor.
     *
     * @param mixed $result      The default result for this command.
     * @param bool  $isMultiLine A bool indicating the response is multiline or not.
     */
    public function __construct($result = null, $isMultiLine = false)
    {
        $this->result = $result;
        $this->isMultiLine = $isMultiLine;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiLine()
    {
        return $this->isMultiLine;
    }

    /**
     * {@inheritdoc}
     */
    public function isCompressed()
    {
        return false;
    }
}
