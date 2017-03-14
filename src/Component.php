<?php
namespace Skel;

class Component implements Interfaces\Component, \JsonSerializable, \Iterator, \Countable {
  protected $keys = array();
  protected $currentKey = null;
  protected $templateName;
  protected $template;
  protected $elements = array();
  protected $context;

  public function __construct(array $elements=array(), Interfaces\Template $template=null, Interfaces\Context $context=null) {
    if ($elements) $this->setElements($elements);
    $this->rewind();
    $this->template = $template;
    $this->context = $context;
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

  public function setContext(Interfaces\Context $c) {
    $this->context = $c;
    if ($this->templateName && ($this->context instanceof Interfaces\App)) $this->setTemplate($c->getTemplate($this->templateName));
    return $this;
  }

  public function getContext() { return $this->context; }

  public function url(string $url) {
    if (!$this->context || !($this->context instanceof \Skel\Interfaces\App)) throw new \Skel\UnpreparedObjectException("You must set a context that implements `\Skel\Interfaces\App` for this component via the `setContext` method before trying to get a resource URL. This is because `getResourceUrl` is a passthrough method that passes the call along to its context.");
    return $this->context->getUrlFor($url);
  }

  public function exportArray() {
    $array = array();
    foreach($this as $k => $v) $array[$k] = $v;
    return $array;
  }

  public function __toString() {
    if (!$this->template) throw new \Skel\UnpreparedObjectException("You must set a template in order to render a component. You can pass any valid \Skel\Interfaces\Template object as the second argument of the constructor for a Component, or you can use the `setTemplate` method to set or change the template after the object is instatiated.");
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

