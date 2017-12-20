<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\PathTraverser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PathTraverserTest extends TestCase
{
    use RefreshDatabase;

    protected $path_traverser;

	public function setUp()
	{
		parent::setUp();
		$this->path_traverser = new PathTraverser;
	}

    /**
     * @test
     * it can find the equation of line between two given points
     */
    public function it_can_find_the_slope_of_line_between_two_given_points()
    {
    	//arrange
        $this->path_traverser->setX1(-2);
        $this->path_traverser->setY1(4);
        $this->path_traverser->setX2(7);
        $this->path_traverser->setY2(-6);
    
        //act
    	$slope = $this->path_traverser->getSlope();
    
        //assert
        $this->assertEquals((-10/9), $slope);
    }

    /**
     * @test
     * it returns the correct value of y when x is given
     */
    public function it_returns_the_correct_value_of_y_when_x_is_given()
    {
    	//arrange
        $this->path_traverser->setX1(-2);
        $this->path_traverser->setY1(4);
        $this->path_traverser->setX2(7);
        $this->path_traverser->setY2(-6);
    
        //act
    	$y = $this->path_traverser->getYWhenX(9);
    
        //assert
        $this->assertEquals(-10 + (16/9), $y);
    }
}
