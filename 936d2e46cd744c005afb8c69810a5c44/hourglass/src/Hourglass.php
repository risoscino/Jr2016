<?php

class Hourglass
{
    /**
     * @var int
     */
    private $height;

    /**
     * @var double
     */
    private $capacity;

    /**
     * Constructor.
     *
     * @param int $height
     * @param double $capacity
     */
    public function __construct($height, $capacity)
    {
        $this->height = $height;
        $this->capacity = $capacity;
    }

    /**
     * Fill both hourglass bulbs.
     *
     * @return string
     */
    public function fillBulbs()
    {
        return $this->drawLine() .
               $this->drawTopBulb() .
               $this->drawBottomBulb();
    }

    /**
     * Draw the top or bottom line of the hourglass.
     *
     * @return string
     */
    private function drawLine()
    {
        $topLine = '';
        $numLines = (2 * $this->height) + 1;

        for ($idx = 1; $idx <= $numLines; $idx++) {
            $topLine .= '_';
        }

        $topLine .= "\n";

        return $topLine;
    }

    /**
     * Draw the top bulb.
     *
     * @return string
     */
    private function drawTopBulb()
    {
        $topBulb = '';
        $sandCount = 0;

        //generate sand for each level based on the height
        for ($level = 1; $level <= $this->height; $level++) {
            //draw spaces
            for ($idx = 1; $idx < $level; $idx++) {
                $topBulb .= ' ';
            }

            //draw left-side line
            $topBulb .= '\\';

            //insert sand
            for ($idx = 0; $idx <= 2 * ($this->height - $level); $idx++) {
                $topBulb .= 'x';
                $sandCount++;
            }

            //draw right-side line
            $topBulb .= "/\n";
        }//for

        return $topBulb;
    }

    /**
     * Draw the bottom bulb.
     *
     * @return string
     */
    private function drawBottomBulb()
    {
        $bottomBulb = '';
        $sandCount = 0;

        //generate sand for each level based on the height
        for ($level = $this->height; $level >= 1; $level--) {
            //draw spaces
            for ($idx = 1; $idx < $level; $idx++) {
                $bottomBulb .= ' ';
            }

            //draw right-side line
            $bottomBulb .= '/';

            //insert sand
            for ($idx = 2 * ($this->height - $level); $idx >= 0; $idx--) {
                $bottomBulb .= 'x';
                $sandCount++;
            }

            //draw left-side line
            $bottomBulb .= "\\\n";
        }//for

        return $bottomBulb;
    }
}//class Hourglass
