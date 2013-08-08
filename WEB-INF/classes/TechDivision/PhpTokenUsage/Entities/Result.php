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

        $result .= '<span>We analyzed '. $this->tokenCount . ' tokens in '. $this->fileCount . ' files in '. number_format($this->duration, 2) .' seconds.</span>
        <canvas id="' . $this->name . '" width="350" height="350"></canvas>
        <script type="text/javascript">var ' . $this->name . 'Data = {
        labels : ["' . implode('","', $this->labels) . '"],
        datasets : [';

        $tmp = array();
        $color = array();
        foreach ($this->data as $token => $data) {

            $color[$token][1] = (int) rand(25, 200);
            $color[$token][2] = (int) rand(25, 200);
            $color[$token][3] = (int) rand(25, 200);

            $tmp[] = '{
                fillColor : "rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',0.5)",
                strokeColor : "rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',1)",
                pointColor : "rgba(' . $color[$token][1] . ',' . $color[$token][2] . ',' . $color[$token][3] . ',1)",
                pointStrokeColor : "#fff",
                data : [' . implode(',', $data) . ']
            }';

        }

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