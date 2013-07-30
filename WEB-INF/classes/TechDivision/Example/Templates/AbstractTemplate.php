<?php

/**
 * TechDivision\Example\Templates\AbstractTemplate
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\Example\Templates;

/**
 * @package     TechDivision\Example
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Johann Zelger <j.zelger@techdivision.com>
 */

class AbstractTemplate
{

    /**
     * Path to template file
     *
     * @var string
     */
    protected $templateFilepath;

    /**
     * Constructor
     *
     * @param string $templateFilepath The path to the template file
     */
    public function __construct($templateFilepath = null)
    {
        $this->setTemplateFilepath($templateFilepath);
    }

    /**
     * Sets template filepath
     *
     * @param $templateFilepath
     * @throws \Exception
     * @returns AbstractTemplate
     */
    public function setTemplateFilepath($templateFilepath)
    {
        // check if the template is available
        if (!file_exists($templateFilepath)) {
            throw new \Exception("Requested template '$templateFilepath' is not available");
        }
        // set template file path
        $this->templateFilepath = $templateFilepath;

        return $this;
    }

    /**
     * Returns the path to the template file to render
     *
     * @return string
     */
    public function getTemplateFilepath()
    {
        return $this->templateFilepath;
    }

    /**
     * Renders the given template
     *
     * @return string
     */
    public function render()
    {
        // turn on output buffering
        ob_start();
        // render template file via php
        require_once $this->templateFilepath;
        // return rendered content
        return ob_get_clean();
    }

}