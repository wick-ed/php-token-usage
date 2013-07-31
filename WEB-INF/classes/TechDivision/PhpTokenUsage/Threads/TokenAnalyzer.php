<?php

namespace TechDivision\PhpTokenUsage\Threads;

class TokenAnalyzer extends Thread
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
     * Holds global storage
     *
     * @var Stackable
     */
    public $data;

    /**
     * @param Stackable $data
     * @param $path
     */
    public function __construct(Stackable $data, $path, $tokens)
    {
        $this->data = $data;
        $this->path = $path;

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
        $startTime = time();




        for ($i = 0; $i < count($items); $i++) {
            if (is_dir($items[$i])) {

                $add = glob($items[$i] . '/*');
                $items = array_merge($items, $add);

            } else {

                if (strrpos($items[$i], '.php') == strlen($items[$i]) - 4) {

                    // Get the name of the framework
                    $frameworkName = str_replace('./', '', $items[$i]);
                    $frameworkName = strstr($frameworkName, '/', true);

                    // The version is useful as well!
                    $frameworkVersion = strstr($frameworkName, ' ');

                    // Now we do not need the version in the name anymore
                    $frameworkName = ltrim(strstr($frameworkName, ' ', true));

                    // Make an array for each framework
                    if (isset($counter[$frameworkName]) === false) {

                        // Prepare an array for the framework itself
                        $counter[$frameworkName] = array();
                    }

                    // Prepare the framework version specific array
                    if (isset($counter[$frameworkName][$frameworkVersion]) === false) {

                        $counter[$frameworkName][$frameworkVersion] = $counterTemplate;
                    }

                    // Increase the overall file counter
                    $counter['files']++;
                    // As well as the framework specific counter
                    $counter[$frameworkName][$frameworkVersion]['files']++;

                    // Get the tokens of the file
                    $tokens = token_get_all(file_get_contents($items[$i]));

                    // Check how many stuff we got
                    foreach ($tokens as $token) {

                        if (is_array($token)) {

                            if ($token[0] === T_CLASS) {
                                // If we got a class, we have to count the requires

                                // Check how many stuff we got
                                foreach ($tokens as $token) {

                                    switch ($token[0]) {

                                        case T_REQUIRE:

                                            $counter[$frameworkName][$frameworkVersion]['require']++;
                                            break;

                                        case T_REQUIRE_ONCE:

                                            $counter[$frameworkName][$frameworkVersion]['requireOnce']++;
                                            break;

                                        case T_INCLUDE:

                                            $counter[$frameworkName][$frameworkVersion]['include']++;
                                            break;

                                        case T_INCLUDE_ONCE:

                                            $counter[$frameworkName][$frameworkVersion]['includeOnce']++;
                                            break;
                                    }
                                }

                                // Check the next file
                                break;
                            }
                        }
                    }
                }
            }
        }

        // get time after download finished
        $endTime = time();
        // calc delta time
        $deltaTime = $endTime - $startTime;
    }
}