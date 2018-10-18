<?php

class Rotation {

    /**
     * Start number
     *
     * @var integer
     */
    public $start;

    /**
     * Wave number, we start at 3 since the first 2 waves are known already.
     *
     * @var integer
     */
    public $wave;

    /**
     * Altered wave number
     *
     * @var integer
     */
    public $altWave = 0;

    /**
     * Total amount of waves
     *
     * @var integer
     */
    public $totalWaves = 63;

    /**
     * Return type of our generated data, 'array' or 'string'
     *
     * @var string
     */
    public $returnType = 'array';

    /**
     * Array where we build our results
     *
     * @var array
     */
    public $data = array();

    /**
     * Array to hold our temp wave data
     *
     * @var array
     */
    public $temp = array();

    public function __construct($start, $wave)
    {
        $this->start = $start;
        $this->wave = $wave;
    }

    private function getLocationName($location)
    {
        $list = array(3, 5, 2, 1, 5, 3, 4, 1, 2, 3, 5, 4, 1, 2, 4);

        $spawn_arr = array(
            '1' => 'nw',
            '2' => 'c',
            '3' => 'se',
            '4' => 's',
            '5' => 'sw'
        );

        return $spawn_arr[$list[$location]];
    }

    private function getEnemyByWave($wave, $jad = false)
    {
        if($jad) {
            return 'TzTok-Jad';
        }
        if($wave >= 31) {
            $this->altWave = $wave - 31;
            return array('name' => 'Ket-Zek', 'level' => 360);
        }

        if($wave >= 15) {
            $this->altWave = $wave - 15;
            return array('name' => 'Yt-MejKot', 'level' => 180);
        }

        if($wave >= 7) {
            $this->altWave = $wave - 7;
            return array('name' => 'Tok-Xil', 'level' => 90);
        }

        if($wave >= 3) {
            $this->altWave = $wave - 3;
            return array('name' => 'Tz-Kek', 'level' => 45);
        }
        $this->altWave = $wave - 1;
        return array('name' => 'Tz-Kih', 'level' => 22);
    }

    private function generateData($start, $i = 0)
    {
        $this->altWave = $this->wave;
        $this->temp = array();

        array_push($this->temp, array('Wave' => $this->wave, 'Enemies' => array()));

        if($this->wave == 63) { // TzTok-Jad
            array_push($this->temp[0]['Enemies'], array('Name' => $this->getEnemyByWave($this->altWave, true),
                                                        'Level' => '702',
                                                        'Location' => $this->getLocationName($start %15)));
            return;
        }

        while($this->altWave > 0) { // Regular waves
            $enemy = $this->getEnemyByWave($this->altWave);

            array_push($this->temp[0]['Enemies'], array('Name' => $enemy['name'],
                                                        'Level' => $enemy['level'],
                                                        'Location' => $this->getLocationName(($start +$i) %15)));
            $i ++;
        }
        return;
    }

    public function build()
    {
        $i = 0;

        while($i < $this->totalWaves - 2) {
            $this->generateData($this->start);

            array_push($this->data, $this->temp);

            $this->start ++;
            $this->wave ++;
            $i ++;
        }

        if($this->returnType == 'json') {
            return json_encode($this->data, true);
        }
        return $this->data;
    }

    public function arrayBuilder() {
        $this->returnType = 'array';
        return $this;
    }

    public function jsonBuilder() {
        $this->returnType = 'json';
        return $this;
    }
}