<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wickb
 * Date: 26.06.13
 * Time: 16:32
 * To change this template use File | Settings | File Templates.
 */
$counter = array();
$counterTemplate = array();
$counterTemplate['require'] = 0;
$counterTemplate['requireOnce'] = 0;
$counterTemplate['include'] = 0;
$counterTemplate['includeOnce'] = 0;
$counterTemplate['files'] = 0;
$counterTemplate['ratio'] = 0;

// FileCounter starts with -1, as we do not want to count this file
$counter['files'] = -1;

$items = glob('./*');

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

// We got some useless files as well
unset($counter[0]);
unset($counter['']);

// Now all we have to get is the ratio for each of the framework versions
// Also lets create a graph for each framework
$fileData = file_get_contents('template.html');

// Add the raw data as a comment
ob_start();
var_dump($counter);
$fileData .= '<!--' . ob_get_contents() . '--!>';
ob_end_clean();

foreach ($counter as $framework => $frameworkData) {

    // The files array entry will lead us to nowhere
    if ($framework === 'files') {

        continue;
    }

    // Create the canvas for the graph
    $fileData .= '<h1>' . $framework . '</h1><canvas id="' . $framework . '" width="400" height="400"></canvas>';

    $chartData = array();
    $labelData = array();
    foreach ($frameworkData as $version => $versionData) {

        // Get the version as part of the labeling
        $labelData[] = '"' . $version . '"';

        $structures = 0;
        $structures = $versionData['require'] + $versionData['requireOnce'] + $versionData['include'] + $versionData['includeOnce'];

        if ($structures >= $versionData['files']) {

            $counter[$framework][$version]['ratio'] = 100;

        } else {

            $counter[$framework][$version]['ratio'] = ($structures / ($versionData['files'] - $structures)) * 100;
        }

        // Get the chart data itself
        $chartData[] = $counter[$framework][$version]['ratio'];
    }

    // Build up the data array
    $fileData .= '<script type="text/javascript">var data = {
	labels : [' . implode(',', $labelData). '],
	datasets : [
		{
			fillColor : "rgba(220,220,220,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			data : [' . implode(',', $chartData) . ']
		}
	]
};';

    // Create the code to initialize the graph
    $fileData .= 'var ' . $framework . ' = document.getElementById("' . $framework . '").getContext("2d");
        var myNewChart = new Chart(' . $framework . ').Line(data);</script>';
}

// Append the body and html closing tags
$fileData .= '</body></html>';
file_put_contents('results.html', $fileData);