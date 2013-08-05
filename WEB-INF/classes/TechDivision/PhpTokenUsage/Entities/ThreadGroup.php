<?php

namespace TechDivision\PhpTokenUsage\Entities;

class ThreadGroup
{
    /**
     * All threads ready?
     *
     * @var boolean
     */
    protected $isReady;

    /**
     * Holds all threads that are controlled by this mutex
     *
     * @var array
     */
    protected $threads;

    /**
     * @param array $threads
     */
    public function __construct(array $threads)
    {
        $this->isReady = false;

        // Check if we got some threads
        foreach ($threads as $thread) {

            if ($thread instanceof \Thread) {

                $this->threads[] = $thread;
            }
        }

        // Now start all threads and wait
        foreach ($this->threads as $thread) {

            $thread->join();
        }

        $this->isReady = true;
    }
}