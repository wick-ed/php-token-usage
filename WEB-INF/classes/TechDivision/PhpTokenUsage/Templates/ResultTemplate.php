<?php

/**
 * TechDivision\PhpTokenUsage\Templates\ResultTemplate
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PhpTokenUsage\Templates;

use TechDivision\PhpTokenUsage\Templates\AbstractTemplate;

/**
 * @package     TechDivision\Example
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Johann Zelger <j.zelger@techdivision.com>
 */

class ResultTemplate extends AbstractTemplate
{

    /**
     * Some properties for template
     *
     * @var string
     */
    protected $webappName;
    protected $baseUrl;
    protected $data;

    /**
     * @param string $webappName
     */
    public function setWebappName($webappName)
    {
        $this->webappName = $webappName;
    }

    /**
     * @return string
     */
    public function getWebappName()
    {
        return $this->webappName;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $baseUrl
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}