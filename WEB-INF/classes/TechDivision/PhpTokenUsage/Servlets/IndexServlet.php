<?php

/**
 * TechDivision\PhpTokenUsage\Servlets\IndexServlet
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PhpTokenUsage\Servlets;

use TechDivision\PhpTokenUsage\Stackables\TokenCounter;
use TechDivision\PhpTokenUsage\Templates\IndexTemplate;
use TechDivision\ServletContainer\Interfaces\Request;
use TechDivision\ServletContainer\Interfaces\Response;
use TechDivision\ServletContainer\Servlets\HttpServlet;

/**
 * @package     TechDivision\Example
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Johann Zelger <j.zelger@techdivision.com>
 */

class IndexServlet extends HttpServlet
{
    /**
     * @param Request $req
     * @param Response $res
     * @throws \Exception
     */
    public function doGet(Request $req, Response $res)
    {
        // build path to template
        $pathToTemplate = $this->getServletConfig()->getWebappPath()
            . DS . 'templates' . DS . 'index.phtml';

        // init template
        $template = new IndexTemplate($pathToTemplate);

        $baseUrl = '/';
        // if the application has NOT been called over a VHost configuration append application folder name
        if (!$this->getServletConfig()->getApplication()->isVhostOf($req->getServerName())) {
            $baseUrl .= $this->getServletConfig()->getApplication()->getName() . '/';
        }

        // set vars in template
        $template->setBaseUrl($baseUrl);
        $template->setRequestUri($req->getUri());
        $template->setWebappName($this->getServletConfig()->getApplication()->getName());

        // set response content by render template
        $res->setContent($template->render());
    }

    /**
     * @param Request $req
     * @param Response $res
     */
    public function doPost(Request $req, Response $res)
    {
        // init global data storage
        $tokenCounter = new TokenCounter();

        var_dump($req);die();

        // iterate all downloads
        foreach (array() as $filename => $url) {
            // init async download instance
            $asyncDownloaders[$filename] = new AsyncDownloader($data, $url, $filename);
            // start downloader
            $asyncDownloaders[$filename]->start();
            // log status message
            echo 'Started download: ' . $url . PHP_EOL;
        }

// wait for all downloaders to be finished
        foreach ($asyncDownloaders as $downloader) {
            $downloader->join();
        }

// echo data from global data storage
        foreach ($data as $filename => $value) {
            echo $value . PHP_EOL;
        }
    }
}