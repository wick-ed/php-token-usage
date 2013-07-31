<?php

namespace TechDivision\PhpTokenUsage\Entities;

class Project
{
    /**
     * Holds the project name.
     *
     * @var string
     */
    public $name;

    /**
     * Holds the different versions which we got for the project.
     *
     * @var array
     */
    public $versions;
}