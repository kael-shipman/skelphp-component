<?php
namespace Skel;

class Component implements Interfaces\Component, \JsonSerializable, \Iterator, \Countable {
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

  public function __clone() {
    $this->template = clone $this->template;
    $this->rewind();
  }




  protected function registerArrayKey($key) {
    if (array_search($key, $this->keys) === false) $this->keys[] = $key;
  }
  protected function unregisterArrayKey($key) {
    if (($k = array_search($key, $this->keys)) !== false) unset($this->keys[$k]);
  }





  // ArrayAccess methods

  public function offsetExists($offset) {
    return array_key_exists($offset, $this->elements);
  }
  public function offsetGet($offset) {
    return $this->elements[$offset];
  }
  public function offsetSet($offset, $value) {
    if ($offset === null) $offset = count($this);
    $this->elements[$offset] = $value;
    $this->registerArrayKey($offset);
    return;
  }
  public function offsetUnset($offset) {
    unset($this->elements[$offset]);
    $this->unregisterArrayKey($offset);
    return;
  }


  // Iterator methods

  public function rewind() { $this->currentKey = 0; }
  public function current() { return $this[$this->keys[$this->currentKey]]; }
  public function key() { return $this->keys[$this->currentKey]; }
  public function next() { $this->currentKey++; }
  public function valid() { return array_key_exists($this->currentKey, $this->keys); }


  // Countable

  public function count() { return count($this->elements); }
}

