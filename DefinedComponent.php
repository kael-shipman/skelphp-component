<?php
namespace Skel;

class DefinedComponent extends Component implements Interfaces\DefinedComponent {
  protected $definedFields = array();

  public function addFields(array $fields) {
    foreach($fields as $f) $this->definedFields[] = $f;
  }
  public function getFields() { return $this->definedFields; }
  public function removeFields(array $fields) {
    foreach($fields as $f) {
      while (($k = array_search($f, $this->definedFields)) !== false) unset($this->definedFields[$k]);
    }
  }

  public function offsetGet($key) {
    if (array_search($key, $this->definedFields) === false) throw new UnknownFieldException("The field `$key` is not in the list of valid fields. You may add it be calling `addFields(array('$key'));` on this object.");
    return parent::offsetGet($key);
  }
  public function offsetSet($key, $val) {
    if (array_search($key, $this->definedFields) === false) throw new UnknownFieldException("The field `$key` is not in the list of valid fields. You may add it be calling `addFields(array('$key'));` on this object.");
    return parent::offsetSet($key, $val);
  }
}


