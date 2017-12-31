<?php

namespace App;

class AngleCalculator
{
	/**
	 * the length of the primary hand in cm
	 * @var [type]
	 */
	protected $primary_hand_length = 15;

	/**
	 * the length of the secondary hand in cm
	 * @var [type]
	 */
	protected $secondary_hand_length = 10;

	/**
	 * the x coordinate of the target point
	 * @var [type]
	 */
	protected $x;

	/**
	 * the y coordinate of the target point
	 * @var [type]
	 */
	protected $y;

    /**
     * @return mixed
     */
    public function getPrimaryHandLength()
    {
        return $this->primary_hand_length;
    }

    /**
     * @param mixed $primary_hand_length
     *
     * @return self
     */
    public function setPrimaryHandLength($primary_hand_length)
    {
        $this->primary_hand_length = $primary_hand_length;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecondaryHandLength()
    {
        return $this->secondary_hand_length;
    }

    /**
     * @param mixed $secondary_hand_length
     *
     * @return self
     */
    public function setSecondaryHandLength($secondary_hand_length)
    {
        $this->secondary_hand_length = $secondary_hand_length;

        return $this;
    }

    /**
     * sets the coordinates of the point
     * @param [type] $x [description]
     * @param [type] $y [description]
     */
    public function setPoint($x, $y)
    {
    	$this->x = $x;
    	$this->y = $y;
    }

    /**
     * returns the distance of the target point from the origin
     * @return [type] [description]
     */
    public function getDistance()
    {
    	return sqrt( ( $this->x * $this->x ) + ( $this->y * $this->y ) );
    }

    /**
     * calculates and returns the area of the arbitrary triangle formed by the target point and the two hands
     * @return [type] [description]
     */
    public function getArea()
    {
    	$perimeter = $this->getPrimaryHandLength() + $this->getSecondaryHandLength() + $this->getDistance();
    	$s = $perimeter / 2;
    	$area = sqrt($s * ($s - $this->getPrimaryHandLength()) * ($s - $this->getSecondaryHandLength()) * ($s - $this->getDistance()));

    	return $area;
    }

    /**
     * calculates and returns the height of the arbitrary triangle formed by the target point and the two sides
     * @return [type] [description]
     */
    public function getTriangleHeight()
    {
    	$area = $this->getArea();
    	$height = (2 * $area) / $this->getDistance();

    	return $height;
    }

    /**
     * returns the angle between the target line and primary hand on the arbitrary triangle 
     * @return [type] [description]
     */
    public function getTargetToPrimaryAngle()
    {
    	$p = $this->getTriangleHeight();
    	$h = $this->getPrimaryHandLength();
    	$ratio = $p / $h;
    	
    	return rad2deg(asin($ratio));
    }

    /**
     * returns the angle between the target and x axis
     * @return [type] [description]
     */
    public function getTargetToXAxisAngle()
    {
    	$p = $this->y;
    	$b = $this->x;
        if ($b == 0) {
            return 0;
        }
    	$ratio = $p / $b;
    	
    	return rad2deg(atan($ratio));
    }

    /**
     * returs the angle between primary hand and x axis
     * @return [type] [description]
     */
    public function getPrimaryHandAngle()
    {
    	return round($this->getTargetToXAxisAngle() - $this->getTargetToPrimaryAngle(), 6);
    }

    /**
     * returns the angle between primary hand and the height of the triangle
     * @return [type] [description]
     */
    public function getPrimaryToHeightAngle()
    {
        return 90 - $this->getTargetToPrimaryAngle();
    }

    /**
     * returns the angle between the secondary hand and the height of the triangle
     * @return [type] [description]
     */
    public function getSecondaryToHeightAngle()
    {
        $h = $this->getSecondaryHandLength();
        $b = $this->getTriangleHeight();
        $ratio = $b / $h;

        return rad2deg(acos($ratio));
    }

    /**
     * returns the angle between the secondary hand and the arbitrary y axis situated at the endpoint of the primary hand
     * @return [type] [description]
     */
    public function getSecondaryHandAngle()
    {
        return round($this->getPrimaryToHeightAngle() + $this->getSecondaryToHeightAngle() - 90, 6);
    }
}
