<?php

use PHPUnit\Framework\TestCase;

class ComponentTests extends TestCase {
  public function testCanCreateComponent() {
    $c = new \Skel\Component();
  }

  public function testRemoveObjectFromCollection() {
    $c = new \Skel\ComponentCollection();
    for($i=0; $i < 10; $i++) $c[] = new \Skel\Component(array('name' => 'test'.$i, 'value' => $i));

    $this->assertTrue($c->contains('name', 'test5'));
    $c5 = $c->filter('name', 'test5')[0];
    $c->remove($c5);
    $this->assertTrue(!$c->contains('name', 'test5'));
    $this->assertEquals(9, count($c));
  }
}

