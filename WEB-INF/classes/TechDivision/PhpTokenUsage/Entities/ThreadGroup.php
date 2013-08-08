<?php

namespace TechDivision\PhpTokenUsage\Entities;

use TechDivision\PhpTokenUsage\Threads\TokenAnalyzer;

class ThreadGroup
{
    /**
     * All threads ready?
     *
     * @var boolean
     */
    public $isReady;

    /**
     * Holds all threads that are controlled by this mutex
     *
     * @var array
     */
    protected $threads = array();

    public $result;

    private $data;

    /**
     * @param $projectName
     * @param \Stackable $data
     * @param array $threads
     */
    public function __construct($projectName, \Stackable $data, array $threads)
    {
        $this->isReady = false;
        $this->data = $data;

        // Check if we got some threads
        foreach ($threads as $thread) {

            if ($thread instanceof TokenAnalyzer) {

                $this->threads[] = $thread;
                $thread->start();
            }
        }

        // Now start all threads and wait
        foreach ($this->threads as $thread) {

            $thread->join();
        }

        // Create a result for this thread group
        $this->result = new Result();
    $this->result->name = $projectName;

        foreach ($this->data as $version => $data) {

            // Get all the labels
            $this->result->labels[] = $version;

            // Get the file and token count
            $this->result->fileCount += $data['files'];
            $this->result->tokenCount += $data['tokens'];

            // Get the time
            $this->result->duration += $data['time'];

            // Unset the stuff we do not need any longer
            unset($data['files']);
            unset($data['tokens']);
            unset($data['time']);

            // Now gather the data by token and version
            foreach ($data as $token => $count) {

                $this->result->data[$token][$version] += $count;
            }
        }

        // Sort the labels
        sort($this->result->labels);

        // Sort the count arrays
        foreach ($this->result->data as $token => $count) {

            sort($this->result->data[$token]);
        }

        $this->isReady = true;
    }
}