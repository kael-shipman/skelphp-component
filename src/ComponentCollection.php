<?php
namespace Skel;

class ComponentCollection extends Component implements Interfaces\ComponentCollection {
  public function pop() {
    if (($k = count($this->keys)) == 0) return null;
    $k = $this->keys[$k-1];
    $v = $this->elements[$k];
    $this->offsetUnset($k);
    return $v;
  }

  public function shift() {
    if (count($this->keys) == 0) return null;
    $k = $this->keys[0];
    $v = $this->elements[$k];
    $this->offsetUnset($k);
    return $v;
  }

  public function unshift(Interfaces\Component $c) {
    $this->keys[] = count($this->keys);
    return array_unshift($this->elements, $c);
  }

  public function remove(Interfaces\Component $c) {
    $i = $this->indexOf($c);
    if ($i !== null) $this->offsetUnset($i);
  }

  public function indexOf(Interfaces\Component $c) {
    foreach($this as $k => $colComp) {
      if ($colComp === $c) return $k;
    }
    return null;
  }

  public function filter(string $key, $val) {
    $result = array();
    foreach($this as $e) {
      if ($e[$key] === $val) $result[] = $e;
    }
    return $result;
  }

  public function getColumn(string $key) {
    $result = array();
    foreach($this as $e) {
      if (!isset($e[$key])) $result[] = null;
      else $result[] = $e[$key];
    }
    return $result;
  }

  public function contains(string $key, $val) {
    return count($this->filter($key, $val)) != 0;
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
    if (!($value instanceof Interfaces\Component)) throw new \InvalidArgumentException("All elements in a ComponentCollection object must implement \Skel\Interfaces\Component.");
    if ($offset === null) $offset = count($this);
    if (!is_int($offset)) {
      foreach($this as $k => $v) $v[$offset] = $value;
      return;
    }
    parent::offsetSet($offset, $value);
  }

  public function offsetUnset($offset) {
      $last = $offset == count($this->elements)-1;
      parent::offsetUnset($offset);
      if (!$last) {
          $newElements = array();
          foreach($this->elements as $k=>$v) {
              if ($k < $offset) $newElements[$k] = $v;
              else $newElements[$offset++] = $v;
          }
          $this->elements = $newElements;

          // Reset keys
          $this->keys = array();
          for ($i = 0; $i < count($this->elements); $i++) $this->keys[] = $i;
      }
  }
}


