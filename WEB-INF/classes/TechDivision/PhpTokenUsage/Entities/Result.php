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
        $result = '';

        $result .= '<span>We analyzed '. $this->tokenCount . ' tokens in '. $this->fileCount . ' files in '. $this->duration .' seconds.</span>
        <script type="text/javascript">var data = {
        labels : [' . implode(',', $this->labels) . '],
        datasets : [';

        $tmp = array();
        $color = array();
        foreach ($this->data as $token => $data) {

            $color[$token] = (int) rand(125, 255);

            $tmp[] = '{
                fillColor : "rgba(' . $color[$token] . ',' . $color[$token] . ',' . $color[$token] . ',0.5)",
                strokeColor : "rgba(' . $color[$token] . ',' . $color[$token] . ',' . $color[$token] . ',1)",
                pointColor : "rgba(' . $color[$token] . ',' . $color[$token] . ',' . $color[$token] . ',1)",
                pointStrokeColor : "#fff",
                data : [' . implode(',', $data) . ']
            }';

        }

        $result .= implode(',', $tmp);
        $result .= ']
        };';

        // Create the legend
        foreach ($this->data as $token => $data) {

            $result .= '<span class="push-left" style="color:#fff; background-color: rgba(' . $color[$token] . ',' . $color[$token] . ',' . $color[$token] . ',0.5)">' . $token . '</span>';
        }

        // Create the code to initialize the graph
        $result .= 'var ' . $this->name . ' = document.getElementById("' . $this->name . '").getContext("2d");
    var myNewChart = new Chart(' . $this->name . ').Line(data);</script>';

        return $result;
    }
}