<?php

namespace TechDivision\PhpTokenUsage\Threads;

use TechDivision\PhpTokenUsage\Entities\Project;
use TechDivision\PhpTokenUsage\Stackables\TokenCounter;

class ProjectAnalyzer extends \Thread
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
     * Holds global storage
     *
     * @var \Stackable
     */
    public $data;

    /**
     * @param \Stackable $data
     * @param $path
     * @param Project $project
     * @param $tokens
     */
    public function __construct($path, Project $project, $tokens)
    {
        $this->path = $path;
        $this->project = $project;

        // Make sure we get an array
        if (!is_array($tokens)) {

            $tokens = array($tokens);
        }

        $this->tokens = $tokens;

        // init global data storage
        $this->data = new TokenCounter();
    }

    /**
     * Runs the analyzer
     */
    public function run()
    {
        // First of all we have to get all the files we need to scan
        $tokenAnalyzers = array();
        foreach ($this->project->versions as $version) {

                $tokenAnalyzers[$version] = new TokenAnalyzer($this->data, $this->path, $this->project, $version, $this->tokens);
                // start analyzing
                $tokenAnalyzers[$version]->start();
        }

        // wait for all threads to be finished
        foreach ($tokenAnalyzers as $tokenAnalyzer) {

            $tokenAnalyzer->join();
        }
    }
}