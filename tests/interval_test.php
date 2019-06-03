<?php

include dirname(__FILE__)."/../app/Interval.php";
include dirname(__FILE__)."/../app/Api.php";

class IntervalTests extends PHPUnit\Framework\TestCase
{
    public function testTypeOfConflictIntesectsLeftMerge(){
        $new = new Interval([
            'date_start' => '2019-05-01',
            'date_end' => '2019-05-09',
            'price' => 15
        ]);

        $old = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-15',
            'price' => 15
        ]);

        $expected = "intersects_left_merge";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

    public function testTypeOfConflictIntesectsRightMerge(){
        $new = new Interval([
            'date_start' => '2019-05-16',
            'date_end' => '2019-05-20',
            'price' => 15
        ]);

        $old = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-15',
            'price' => 15
        ]);

        $expected = "intersects_right_merge";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

    public function testTypeOfConflictIntesectsRight(){
        $old = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-15',
            'price' => 15
        ]);
        $new = new Interval([
            'date_start' => '2019-05-13',
            'date_end' => '2019-05-20',
            'price' => 20
        ]);

        $expected = "intersects_right";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

    public function testTypeOfConflictIntesectsLeft(){
        $new = new Interval([
            'date_start' => '2019-05-05',
            'date_end' => '2019-05-12',
            'price' => 20
        ]);
        $old = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-15',
            'price' => 15
        ]);

        $expected = "intersects_left";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

    public function testTypeOfConflictNewInsideOld(){
        $new = new Interval([
            'date_start' => '2019-05-11',
            'date_end' => '2019-05-19',
            'price' => 20
        ]);
        $old = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-20',
            'price' => 15
        ]);

        $expected = "new_inside_old";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

    public function testTypeOfConflictNewInsideOldMerge(){
        $new = new Interval([
            'date_start' => '2019-05-11',
            'date_end' => '2019-05-19',
            'price' => 15
        ]);
        $old = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-20',
            'price' => 15
        ]);

        $expected = "new_inside_old_merge";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

    public function testTypeOfConflictOldInsideNew(){
        $new = new Interval([
            'date_start' => '2019-05-10',
            'date_end' => '2019-05-20',
            'price' => 20
        ]);
        $old = new Interval([
            'date_start' => '2019-05-12',
            'date_end' => '2019-05-15',
            'price' => 15
        ]);

        $expected = "old_inside_new";

        $this->assertSame($expected,$new->typeOfconflict($old));
    }

}
