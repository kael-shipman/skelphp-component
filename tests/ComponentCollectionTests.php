<?php

class ComponentCollectionTests extends PHPUnit\Framework\TestCase {
    public function testCanCreateEmptyComponentCollection() {
        new \Skel\ComponentCollection();
    }
    public function testCanCreateComponentCollectionWithElements() {
        new \Skel\ComponentCollection(array(new \Skel\Component(), new \Skel\Component()));
    }
    public function testCanCreateComponentCollectionWithTemplate() {
        new \Skel\ComponentCollection(array(), new \Skel\StringTemplate('',false));
    }

    public function testFunction_Contains_() {
        $c = $this->getTestCollection();
        $this->assertTrue($c->contains('name', 'test5'), 'Should contain "test5" element');
        $this->assertFalse($c->contains('name', 'non-existentElement'), 'Should not contain "non-existentElement" element');
        $this->assertFalse($c->contains('nonExistentKey', 'nope'), 'Should not contain an element with nonExistentKey');

        $c[] = new \Skel\Component(array('testBooleanFalse' => false));
        $this->assertTrue($c->contains('testBooleanFalse', false), 'Should contain an element "testBooleanFalse" whose value is false');

        $c[] = new \Skel\Component(array('testNull' => null));
        $this->assertTrue($c->contains('testNull', null), 'Should contain an element "testNull" whose value is null');
    }

    public function testFunction_Filter_() {
        $c = $this->getTestCollection();
        $c5 = $c->filter('name', 'test5');
        $this->assertEquals(1, count($c5), "Filter should return one element named c5");

        $c[7]['value'] = 5;
        $c[8]['value'] = 5;
        $c[9]['value'] = 5;
        $c5 = $c->filter('value', 5);
        $this->assertEquals(4, count($c5), "Filter should return 4 elements whose value is 5");
    }

    public function testFunction_Remove_() {
        $c = $this->getTestCollection();
        $this->assertTrue($c->contains('name', 'test5'));
        $this->assertEquals(10, count($c));
        $this->assertEquals(4, $c[4]['value'], "The 4th element should have a value equal to 4");
        $this->assertEquals(6, $c[6]['value'], "The 6th element should have a value equal to 6");

        $c5 = $c->filter('name', 'test5')[0];
        $c->remove($c5);

        $this->assertFalse($c->contains('name', 'test5'));
        $this->assertEquals(9, count($c));
        $this->assertEquals(4, $c[4]['value'], "The 4th element should have a value equal to 4");
        $this->assertEquals(7, $c[6]['value'], "The 6th element should have a value equal to 7");
    }

    public function testFunction_Pop_() {
        $c = $this->getTestCollection();
        $this->assertTrue($c->contains('name', 'test9'));
        $this->assertEquals(10, count($c));
        $this->assertEquals(9, $c[9]['value']);

        $c9 = $c->pop();

        $this->assertFalse($c->contains('name', 'test9'));
        $this->assertEquals(9, count($c));
        $this->assertEquals(8, $c[8]['value']);
        $this->assertEquals(9, $c9['value']);
    }

    public function testFunction_Shift_() {
        $c = $this->getTestCollection();
        $this->assertTrue($c->contains('name', 'test0'));
        $this->assertEquals(10, count($c));
        $this->assertEquals(0, $c[0]['value']);

        $c0 = $c->shift();

        $this->assertFalse($c->contains('name', 'test0'));
        $this->assertEquals(9, count($c));
        $this->assertEquals(1, $c[0]['value']);
        $this->assertEquals(9, $c[8]['value']);
        $this->assertEquals(0, $c0['value']);
    }

    public function testFunction_Unshift_() {
        $c = $this->getTestCollection();
        $this->assertFalse($c->contains('name', 'test-1'));
        $this->assertEquals(10, count($c));
        $this->assertEquals(0, $c[0]['value']);

        $c->unshift(new \Skel\Component(array('name' => 'test-1', 'value' => -1)));

        $this->assertTrue($c->contains('name', 'test-1'));
        $this->assertEquals(11, count($c));
        $this->assertEquals(-1, $c[0]['value']);
        $this->assertEquals(9, $c[10]['value']);
    }

    public function testFunction_GetColumn_() {
        $c = $this->getTestCollection();
        $vals = $c->getColumn('value');
        $this->assertEquals(10, count($vals));
        $this->assertEquals(0, $vals[0]);
        $this->assertEquals(9, $vals[9]);

        $nope = $c->getColumn('nope');
        $this->assertEquals(10, count($nope));
        $this->assertEquals(null, $nope[0]);
        $this->assertEquals(null, $nope[9]);
    }

    public function testFunction_IndexOf_() {
        $c = $this->getTestCollection();
        $c5 = $c[5];
        $this->assertEquals(5, $c5['value']);

        $nope = new \Skel\Component(array('name' => 'noexistent', 'value' => 10000));
        $this->assertNull($c->indexOf($nope));

        $nope = new \Skel\Component(array('name' => 'test5', 'value' => 5));
        $this->assertNull($c->indexOf($nope));
    }






    protected function getTestCollection() {
        $c = new \Skel\ComponentCollection();
        for($i=0; $i < 10; $i++) $c[] = new \Skel\Component(array('name' => 'test'.$i, 'value' => $i));
        return $c;
    }
}

