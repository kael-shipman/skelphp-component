<?php
namespace Skel;

class ComponentCollection extends Component implements Interfaces\ComponentCollection {
  public function pop() {
    return array_pop($this->elements);
  }

  public function shift() {
    return array_shift($this->elements);
  }

  public function unshift(Interfaces\Component $c) {
    return array_unshift($this->elements, $c);
  }

  public function remove(Interfaces\Component $c) {
    $i = $this->indexOf($c);
    if ($i !== null) unset($this->elements[$i]);
  }

  public function indexOf(Interfaces\Component $c) {
    for ($i = 0; $i < count($this->elements); $i++) {
      if ($this->elements[$i] == $c) return $i;
    }
    return null;
  }




  // Method overrides

  public function __toString() {
    $elements = array();
    foreach($this as $k => $e) {
      if ($this->template && !$e->getTemplate()) $e->setTemplate($this->template);
      $elements[] = (string)$e;
    }
    return implode("\n", $elements);
  }

  public function jsonSerialize() {
    return $this->elements;
  }

  public function offsetSet($offset, $value) {
    if (!($value instanceof Interfaces\Component)) throw new \InvalidArgumentException("All elements in a ComponentCollection object must be implement \Skel\Interfaces\Component.");
    if (!is_int($offset)) {
      foreach($this as $k => $v) $v[$offset] = $value;
      return;
    }
    parent::offsetSet($offset, $value);
  }
}
