<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wickb
 * Date: 07.08.13
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */

namespace TechDivision\PhpTokenUsage\Entities;

class Result
{
    public $name = '';

    public $labels = array();

    public $fileCount = 0;

    public $duration = 0.0;

    public $data = array();

    public $tokenCount = 0;

    /**
     * @return string
     */
    public function createString()
    {
        // We pre-build the data part of our string, so we got the colors already for later (earlier) use
        $tmp = array();
        $color = array();
        foreach ($this->data as $token => $data) {

            $color[$token][1] = (int) rand(150, 255);
            $color[$token][2] = (int) rand(25, 150);
            $color[$token][3] = (int) rand(25, 150);

            $tmp[] = '{
                fillColor : "rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',0.5)",
                strokeColor : "rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',1)",
                pointColor : "rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',1)",
                pointStrokeColor : "#fff",
                data : [' . implode(',', $data) . ']
            }';

        }

        // Now as we as we got the different colors, we can create one for the headline
        $masterColor = array();
        $masterColor[1] = $masterColor[2] = $masterColor[3] = 0;
        $tokenCount = count($this->data);
        foreach ($color as $colorAspect) {

            $masterColor[1] += $colorAspect[1] / $tokenCount;
            $masterColor[2] += $colorAspect[2] / $tokenCount;
            $masterColor[3] += $colorAspect[3] / $tokenCount;
        }

        $result = '';

        $result .= '
        <h3>For <span style="color:rgba(' . (int) $masterColor[1] . ',' . (int) $masterColor[2] . ',' . (int) $masterColor[3] . ',1);">'. $this->name . '</span></h3>
        <span>...we analyzed <span style="color:rgba(' . (int) $masterColor[1] . ',' . (int) $masterColor[2] . ',' . (int) $masterColor[3] . ',1);">'. $this->tokenCount . '</span> tokens within <span style="color:rgba(' . (int) $masterColor[1] . ',' . (int) $masterColor[2] . ',' . (int) $masterColor[3] . ',1);">'. $this->fileCount . '</span> files in <span style="color:rgba(' . (int) $masterColor[1] . ',' . (int) $masterColor[2] . ',' . (int) $masterColor[3] . ',1);">'. number_format($this->duration, 2) . '</span> seconds.</span>
        <canvas id="' . $this->name . '" width="450" height="350"></canvas>
        <script type="text/javascript">var ' . $this->name . 'Data = {
        labels : ["' . implode('","', $this->labels) . '"],
        datasets : [';
        $result .= implode(',', $tmp);
        $result .= ']
        };';

        // Create the code to initialize the graph
        $result .= '
        var ' . $this->name . ' = document.getElementById("' . $this->name . '").getContext("2d");
        var ' . $this->name . 'Chart = new Chart(' . $this->name . ').Line(' . $this->name . 'Data);
        </script>';

        // Create the legend
        foreach ($this->data as $token => $data) {

            $result .= '<span class="push-left token" style="color:#fff; background-color: rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',1)">' . token_name($token) . '</span>';
        }

        return $result;
    }
}