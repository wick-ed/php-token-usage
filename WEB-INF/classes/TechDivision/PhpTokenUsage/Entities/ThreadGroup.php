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

        // We have to compare the version which are part of our data object
        $sortedData = array();
        $tmp = array();
        foreach ($this->data as $version => $data) {

            $tmp[] = $version;
        }

        // Now sort our version array using the versionSort mechanism
        usort($tmp, array('self', 'versionSort'));
        $tmp = array_flip($tmp);

        // Set them all 0
        foreach ($tmp as $version => $value) {

            $tmp[$version] = 0;
        }

        // Lets get the first element and use token count as a basis
        // for our multiplier. The multiplier is used to make the usage counter comparable.
        $firstSet = reset($this->data);
        $countMultiplier = $firstSet['tokens'];

        foreach ($this->data as $version => $data) {

            // Get all the labels
            $this->result->labels[] = $version;

            // Get the file and token count
            $this->result->fileCount += $data['files'];
            $this->result->tokenCount += $data['tokens'];

            // We need the current token count for our multiplier
            $currentMultiplier = $data['tokens'];

            // Get the time
            $this->result->duration += $data['time'];

            // Unset the stuff we do not need any longer
            unset($data['files']);
            unset($data['tokens']);
            unset($data['time']);

            // Now gather the data by token and version
            foreach ($data as $token => $count) {

                // Preset the data array so we got a sorted array
                if (!isset($this->result->data[$token])) {
                    $this->result->data[$token] = $tmp;
                }

                $this->result->data[$token][$version] += $count * ($currentMultiplier / $countMultiplier);
            }
        }

        // Sort the labels
        sort($this->result->labels);

        $this->isReady = true;
    }

    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    private static function versionSort($a, $b)
    {
        return version_compare($a, $b);
    }
}