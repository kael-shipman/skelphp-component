<?php
namespace Skel;

class Component implements Interfaces\Component, \JsonSerializable, \Iterator {
  protected $keys = array();
  protected $currentKey = null;
  protected $template;
  protected $elements = array();

  public function __construct(array $elements=array(), Interfaces\Template $template=null) {
    if ($elements) $this->setElements($elements);
    $this->rewind();
    $this->template = $template;
  }

  public function getTemplate() { return $this->template; }

  public function jsonSerialize() {
    return array('elements' => $this->elements);
  }

  public function render() {
    return (string)$this;
  }

  public function setElements(array $elements) {
    foreach($elements as $k => $v) $this[$k] = $v;
    return $this;
  }

  public function setTemplate(Interfaces\Template $template) {
    $this->template = $template;
    return $this;
  }

  public function __toString() {
    $elements = array();
    foreach($this as $k => $e) $elements[$k] = $e;
    $elements['component'] = $this;
    return $this->template->render($elements);
  }





  // ArrayAccess methods

  public function offsetExists($offset) {
    return array_key_exists($offset, $this->elements);
  }
  public function offsetGet($offset) {
    return $this->elements[$offset];
  }
  public function offsetSet($offset, $value) {
    $this->elements[$offset] = $value;
    if (array_search($offset, $this->keys) === false) $this->keys[] = $offset;
  }
  public function offsetUnset($offset) {
    unset($this->elements[$offset]);
    $k = array_search($offset, $this->keys);
    if ($k !== false) unset($this->keys[$k]);
  }


  // Iterator methods

  public function rewind() { $this->currentKey = 0; }
  public function current() { return $this->elements[$this->keys[$this->currentKey]]; }
  public function key() { return $this->keys[$this->currentKey]; }
  public function next() { $this->currentKey++; }
  public function valid() { return array_key_exists($this->currentKey, $this->keys); }
}

