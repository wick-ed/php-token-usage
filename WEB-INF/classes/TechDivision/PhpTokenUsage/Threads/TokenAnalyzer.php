<?php

namespace TechDivision\PhpTokenUsage\Threads;

use TechDivision\PhpTokenUsage\Entities\Project;

class TokenAnalyzer extends \Thread
{
    /**
     * Holds the path of the folder to analyze files in
     *
     * @var string
     */
    public $path;

    /**
     * The tokens to search for
     *
     * @var array
     */
    public $tokens;

    /**
     * The project we have to scan
     *
     * @var Project
     */
    public $project;

    /**
     * Holds the specific version of the used project
     *
     * @var string
     */
    public $version;

    /**
     * Holds global storage
     *
     * @var \Stackable
     */
    public $data;

    /**
     * @param \Stackable $data
     * @param $path
     * @param Project $project
     * @param $version
     * @param $tokens
     */
    public function __construct(\Stackable $data, $path, Project $project, $version, $tokens)
    {
        $this->data = $data;
        $this->path = $path;
        $this->project = $project;
        $this->version = $version;

        // Make sure we get an array
        if (!is_array($tokens)) {

            $tokens = array($tokens);
        }

        $this->tokens = $tokens;
    }

    /**
     * Runs the analyzer
     */
    public function run()
    {
        // get current start time
        $startTime = microtime(true);

        $data = array();

        $data[$this->version] = array();
        $data[$this->version]['files'] = 0;
        $data[$this->version]['tokens'] = 0;

        // Initiate the arrays
        foreach ($this->tokens as $token) {

            $data[$this->version][$token] = 0;
        }

        // Now traverse over all the files there are for this version
        $items = glob($this->path . DS . $this->project->name . DS . $this->version . DS . '*');
        $files = array();

        for ($i = 0; $i < count($items); $i++) {

            if (is_dir($items[$i]) && ($items[$i] !== '.' && $items[$i] !== '..')) {

                $items = array_merge($items, glob($items[$i] . DS . '*'));

            } else {

                // Check if we got a php file
                if (strrpos($items[$i], '.php') == strlen($items[$i]) - 4) {

                    $files[] = $items[$i];
                }
            }
        }

        // Now traverse over all the files
        $searchedTokens = array_flip($this->tokens);
        $data[$this->version]['files'] = count($files);
        foreach ($files as $file) {

            // First of all we get the tokens
            $tokens = token_get_all(file_get_contents($file));

            // Scan all the tokens
            $counter = count($tokens);
            $data[$this->version]['tokens'] += $counter;
            for ($i = 0; $i < $counter; $i++) {

                if (is_array($tokens[$i]) && isset($searchedTokens[$tokens[$i][0]])) {

                    $data[$this->version][$tokens[$i][0]]++;
                }
            }
        }

        // get time after download finished
        $endTime = microtime(true);
        // calc delta time
        $data[$this->version]['time'] = $endTime - $startTime;

        // Give our results to the stackable
        $this->data[$this->version] = $data[$this->version];
    }
}