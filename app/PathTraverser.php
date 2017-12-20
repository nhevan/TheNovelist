<?php

namespace App;

class PathTraverser
{
    protected $X1;
    protected $Y1;
    protected $X2;
    protected $Y2;


    /**
     * @return mixed
     */
    public function getX1()
    {
        return $this->X1;
    }

    /**
     * @param mixed $X1
     *
     * @return self
     */
    public function setX1($X1)
    {
        $this->X1 = $X1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getY1()
    {
        return $this->Y1;
    }

    /**
     * @param mixed $Y1
     *
     * @return self
     */
    public function setY1($Y1)
    {
        $this->Y1 = $Y1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getX2()
    {
        return $this->X2;
    }

    /**
     * @param mixed $X2
     *
     * @return self
     */
    public function setX2($X2)
    {
        $this->X2 = $X2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getY2()
    {
        return $this->Y2;
    }

    /**
     * @param mixed $Y2
     *
     * @return self
     */
    public function setY2($Y2)
    {
        $this->Y2 = $Y2;

        return $this;
    }

    /**
     * returns the slope of the line between the 2 points
     * @return [type] [description]
     */
    public function getSlope()
    {
    	return ($this->Y2 - $this->Y1) / ($this->X2 - $this->X1);
    }

    /**
     * returns the value of calculated y when x is given
     * @param  [type] $x [description]
     * @return [type]    [description]
     */
    public function getYWhenX($x)
    {
    	return ( $this->getSlope() * ($x - $this->getX1()) ) + $this->getY1();
    }
}
