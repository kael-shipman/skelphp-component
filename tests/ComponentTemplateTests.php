<?php

use PHPUnit\Framework\TestCase;

class ComponentTemplateTests extends TestCase {
  protected $genericElements;
  protected $correctElements;

  public function testThrowsErrorOnUnpreparedRender() {
    $t = $this->getTemplate();

    try {
      $t->render($this->getGenericElements());
      $this->fail('Should have thrown an InvalidArgumentException on render without component element');
    } catch (\InvalidArgumentException $e) {
      $this->assertTrue(true, 'This is the correct behavior');
    }

    try {
      $t->render($this->getCorrectElements());
      $this->fail('Should have thrown an error on render without langParser');
    } catch (\Skel\UnpreparedObjectException $e) {
      $this->assertTrue(true, 'This is the correct behavior');
    }

    $t->setLangParser(new \Skel\LangParser());
    $result = $t->render($this->getCorrectElements());
    $this->assertTrue(true, 'No exceptions should have been thrown while rendering with correct elements and langParser');
  }

  public function testSubstitutesVariables() {
    $t = $this->getTemplate();
    $t->setLangParser(new \Skel\LangParser());
    $result = $t->render($this->getCorrectElements());
    $expected = 'Prop1: 1, Prop2: 2, Prop3: 1, Prop4: a string';
    $this->assertTrue(substr($result, 0, strlen($expected)) == $expected);
  }

  public function testSubstitutesMethods() {
    $t = $this->getTemplate();
    $t->setLangParser(new \Skel\LangParser());
    $result = $t->render($this->getCorrectElements());
    $expected = 'Custom Method: my stringmy string';
    $this->assertTrue(strpos($result, $expected) !== false);
  }

  public function testSilentlyIgnoreUnknownVars() {
    $t = $this->getTemplate();
    $t->setLangParser(new \Skel\LangParser());
    $result = $t->render($this->getCorrectElements());
    $expected = 'Nope: ';
    $this->assertTrue(substr($result, (-1*strlen($expected))) == $expected);
  }












  protected function getGenericElements() {
    if (!$this->genericElements) $this->genericElements = array(
      'prop1' => 1,
      'prop2' => 2,
      'prop3' => true,
      'prop4' => 'a string',
    );

    return $this->genericElements;
  }

  protected function getCorrectElements() { 
    if (!$this->correctElements) {
      $this->correctElements = $this->getGenericElements();
      $this->correctElements['component'] = new \Skel\TestComponent();
    }
    return $this->correctElements;
  }

  protected function getTemplate() {
    return new \Skel\ComponentTemplate('Prop1: @@prop1@@, Prop2: @@prop2@@, Prop3: @@prop3@@, Prop4: @@prop4@@, Custom Method: @@testMethod(2, \'my string\')@@, Nope: @@nonexistentVar@@', false);
  }

  
}

