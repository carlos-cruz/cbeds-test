<?php

include dirname(__FILE__)."/../app/Interval.php";

class IntervalTests extends PHPUnit\Framework\TestCase
{

    public function testTypeOfConflict(){
        $new = new Interval([
            'date_start' => '2019-05-16',
            'date_end' => '2019-05-30',
            'price' => 25
        ]);

        $old = new Interval([
            'date_start' => '2019-05-01',
            'date_end' => '2019-05-17',
            'price' => 15
        ]);

        $expected = "intersects_right";

        $this->assertSame($expected, $new->typeOfconflict($old));

        $new = new Interval([
            'date_start' => '2019-05-17',
            'date_end' => '2019-05-30',
            'price' => 15
        ]);

        $old = new Interval([
            'date_start' => '2019-05-01',
            'date_end' => '2019-05-17',
            'price' => 15
        ]);

        $expected = "merge_right";

        $this->assertSame($expected, $new->typeOfconflict($old));

        $new = new Interval([
            'date_start' => '2019-05-01',
            'date_end' => '2019-05-05',
            'price' => 15
        ]);

        $old = new Interval([
            'date_start' => '2019-05-05',
            'date_end' => '2019-05-10',
            'price' => 15
        ]);

        $expected = "merge_left";

        $this->assertSame($expected, $new->typeOfconflict($old));

        $new = new Interval([
            'date_start' => '2019-05-03',
            'date_end' => '2019-05-10',
            'price' => 15
        ]);

        $old = new Interval([
            'date_start' => '2019-05-01',
            'date_end' => '2019-05-05',
            'price' => 15
        ]);

        $expected = "intersects_right_merge";

        $this->assertSame($expected, $new->typeOfconflict($old));
    }
}
