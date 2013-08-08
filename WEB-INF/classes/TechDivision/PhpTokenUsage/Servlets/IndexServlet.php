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

use TechDivision\PhpTokenUsage\Entities\Project;
use TechDivision\PhpTokenUsage\Entities\Result;
use TechDivision\PhpTokenUsage\Entities\ThreadGroup;
use TechDivision\PhpTokenUsage\Stackables\TokenCounter;
use TechDivision\PhpTokenUsage\Templates\IndexTemplate;
use TechDivision\PhpTokenUsage\Templates\ResultTemplate;
use TechDivision\PhpTokenUsage\Threads\TokenAnalyzer;
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
     * Path to the data directory.
     */
    const DATA_PATH = "/../../../../../META-INF/data/projects/";

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
        $template->setWebappName($this->getServletConfig()->getApplication()->getName());
        // Especially the list of projects is important
        $template->setProjects($this->getProjects());

        // set response content by render template
        $res->setContent($template->render());
    }

    /**
     * @param Request $req
     * @param Response $res
     */
    public function doPost(Request $req, Response $res)
    {
        // load the params with the entity data
        $parameterMap = $req->getParameterMap();
        /*var_dump($parameterMap);die();
        // check if the necessary params has been specified and are valid
        if (!array_key_exists('tokenList[]', $parameterMap)) {

            throw new \Exception('You did not pass any valid tokens');

        } else {

            foreach($parameterMap['tokenList[]'] as $token) {

                if ($token !== 0) {

                    $tokens = filter_var($token, FILTER_SANITIZE_STRING);
                }
            }
        }*/

        $tokens = array(T_REQUIRE, T_REQUIRE_ONCE, T_INCLUDE, T_INCLUDE_ONCE);

        // build path to template
        $pathToTemplate = $this->getServletConfig()->getWebappPath()
            . DS . 'templates' . DS . 'result.phtml';

        // init template
        $template = new ResultTemplate($pathToTemplate);

        $baseUrl = '/';
        // if the application has NOT been called over a VHost configuration append application folder name
        if (!$this->getServletConfig()->getApplication()->isVhostOf($req->getServerName())) {
            $baseUrl .= $this->getServletConfig()->getApplication()->getName() . '/';
        }

        // set vars in template
        $template->setBaseUrl($baseUrl);
        $template->setWebappName($this->getServletConfig()->getApplication()->getName());

        // Initialize the thread array
        $tokenAnalyzers = array();
        $path = realpath(__DIR__ . self::DATA_PATH);

        // Traverse over all projects
        $projects = $this->getProjects();
        foreach ($projects as $project) {

            // If we got something, create a thread
            if (isset($parameterMap[strtolower($project->name)])) {

                // Traverse over all versions and create an analyzer thread for each of them
                $tmp = array();
                // init global data storage
                $tokenCounter = new TokenCounter();

                foreach ($project->versions as $version) {

                    // If we got something, create a thread
                    $tmp[] = new TokenAnalyzer($tokenCounter, $path, $project, $version, $tokens);
                }

                $tokenAnalyzers[$project->name] = new ThreadGroup($project->name, $tokenCounter, $tmp);
            }
        }

        // Join them all as we cannot use websockets by now.
        $results = array();
        foreach ($tokenAnalyzers as $tokenAnalyzerGroup) {

            while ($tokenAnalyzerGroup->isReady === false) {
            }

            $results[] = $tokenAnalyzerGroup->result;
        }

        $template->setData($results);

        // set response content by render template
        $res->setContent($template->render());
    }

    /**
     * Will return a list of available projects
     *
     * @return array
     */
    private function getProjects()
    {
        $projects = array();
        $path = realpath(__DIR__ . self::DATA_PATH);
        $items = scandir($path);

        for ($i = 0; $i < count($items); $i++) {
            if (is_dir($path . DS . $items[$i]) && ($items[$i] !== '.' && $items[$i] !== '..')) {

                // Make a new project entity and set the name.
                $project = new Project();
                $project->name = $items[$i];

                $versionItems = scandir($path . DS . $items[$i]);

                // Now traverse over all the subfolders and add them to the version array
                for ($j = 0; $j < count($versionItems); $j++) {
                    if (is_dir($path . DS . $items[$i] . DS . $versionItems[$j]) && ($versionItems[$j] !== '.' && $versionItems[$j] !== '..')) {

                        $project->versions[] = $versionItems[$j];
                    }
                }

                // Add the project to our list
                $projects[] = $project;
            }
        }

        return $projects;
    }
}