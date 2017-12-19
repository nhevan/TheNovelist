<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\AngleCalculator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AngleCalculatorTest extends TestCase
{
	protected $calculator;

	public function setUp()
	{
		parent::setUp();
		$this->calculator = new AngleCalculator;
	}

    /**
     * @test
     * it can set primary hand length
     */
    public function it_can_set_primary_hand_length()
    {
        //act
        $primary_hand_length = $this->calculator->setPrimaryHandLength(15)->getPrimaryHandLength();
    
        //assert
        $this->assertEquals(15, $primary_hand_length);
    }

    /**
     * @test
     * it can set secondary hand length
     */
    public function it_can_set_secondary_hand_length()
    {
        //act
        $secondary_hand_length = $this->calculator->setSecondaryHandLength(15)->getSecondaryHandLength();
    
        //assert
        $this->assertEquals(15, $secondary_hand_length);
    }

    /**
     * @test
     * it can calculate the distance of a given point from the origin
     */
    public function it_can_calculate_the_distance_of_a_given_point_from_the_origin()
    {
    	//arrange
    	$x = 30;
    	$y = 20;
    	$this->calculator->setPoint($x, $y);
    
        //act
    	$distance = $this->calculator->getDistance();
    
        //assert
        $this->assertEquals(36.05551, $distance, '', 0.00001);
    }

    /**
     * @test
     * it can calculate the area of the arbitrary triangle created by the point and the two hands
     */
    public function it_can_calculate_the_area_of_the_arbitrary_triangle_created_by_the_point_and_the_two_hands()
    {
    	//arrange
    	$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
    	$this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin

        //act
    	$area = $this->calculator->getArea();
    
        //assert
        $this->assertEquals(97.42785, $area, '', 0.00001);
    }

    /**
     * @test
     * it can find the height of the arbitrary triangle
     */
    public function it_can_find_the_height_of_the_arbitrary_triangle()
    {
    	//arrange
        $this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
    	$this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
    	$height = $this->calculator->getTriangleHeight();
    
        //assert
        $this->assertEquals(12.99038, $height, '', 0.00001);
    }

    /**
     * @test
     * it can calculate the angle formed by the target point line and primary hand
     */
    public function it_can_calculate_the_angle_formed_by_the_target_point_line_and_primary_hand()
    {
    	//arrange
    	$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
    	$this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
    	$angle = $this->calculator->getTargetToPrimaryAngle();

        //assert
        $this->assertEquals(60.000000, $angle, '', 0.00001); // each angle of a equilateral triangle has a angle of 60 deg
    }

    /**
     * @test
     * it can calculate the angle formed by the target point line and x axis
     */
    public function it_can_calculate_the_angle_formed_by_the_target_point_line_and_x_axis()
    {
    	//arrange
    	$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
    	$this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
    	$angle = $this->calculator->getTargetToXAxisAngle();

        //assert
        $this->assertEquals(45.000000, $angle, '', 0.00001); // each angle of a equilateral triangle has a angle of 60 deg
    }

    /**
     * @test
     * it can pass neelas first scenario
     */
    public function it_can_pass_neelas_first_scenario()
    {
    	//arrange
        $this->calculator->setPrimaryHandLength(17.2);
    	$this->calculator->setSecondaryHandLength(13.9);
    	$this->calculator->setPoint(2.6, 12.7);

        //act
    	$angle = $this->calculator->getTargetToPrimaryAngle();

        //assert
        $this->assertEquals(52, floor($angle));
    }

    /**
     * @test
     * it can pass neelas second scenario
     */
    public function it_can_pass_neelas_second_scenario()
    {
    	//arrange
        $this->calculator->setPrimaryHandLength(16.4);
    	$this->calculator->setSecondaryHandLength(8.2);
    	$this->calculator->setPoint(7.7, 20.5);

        //act
    	$angle = $this->calculator->getTargetToPrimaryAngle();

        //assert
        $this->assertEquals(18.5, round($angle, 1));
    }

    /**
     * @test
     * it can return the primary hand angle with x axis
     */
    public function it_can_return_the_primary_hand_angle_with_x_axis()
    {
    	//arrange
        $this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
    	$this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
    	$angle = $this->calculator->getPrimaryHandAngle();
    
        //assert
        $this->assertEquals(15, abs(round($angle)));
    }

    /**
     * @test
     * it can find the angle between primary hand and the height of the triangle
     */
    public function it_can_find_the_angle_between_primary_hand_and_the_height_of_the_triangle()
    {
        //arrange
        $this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
        $this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
        $angle = $this->calculator->getPrimaryToHeightAngle();
    
        //assert
        $this->assertEquals(30, abs(round($angle)));
    }

    /**
     * @test
     * it can find the angle between secondary hand and height of the triangle
     */
    public function it_can_find_the_angle_between_secondary_hand_and_height_of_the_triangle()
    {
        //arrange
        $this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
        $this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
        $angle = $this->calculator->getSecondaryToHeightAngle();
    
        //assert
        $this->assertEquals(30, abs(round($angle)));
    }

    /**
     * @test
     * it can calculate the angle that the secondary hand needs to move
     */
    public function it_can_calculate_the_angle_that_the_secondary_hand_needs_to_move()
    {
        //arrange
        $this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(15);
        $this->calculator->setPoint(10.60660, 10.60660); // a point with a distance of 15cm from the origin
    
        //act
        $angle = $this->calculator->getSecondaryHandAngle();
    
        //assert
        $this->assertEquals(30, abs(round($angle)));
    }
}
