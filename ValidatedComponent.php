<?php
namespace Skel;

class ValidatedComponent extends Component implements Interfaces\ValidatedComponent {
  protected $validFields = array();

  public function addValidFields(array $fields) {
    foreach($fields as $f) $this->validFields[] = $f;
  }
  public function getValidFields() { return $this->validFields; }
  public function removeValidFields(array $fields) {
    foreach($fields as $f) {
      while (($k = array_search($f, $this->validFields)) !== false) unset($this->validFields[$k]);
    }
  }

  public function offsetGet($key) {
    if (array_search($key, $this->validFields) === false) throw new UnknownFieldException("The field `$key` is not in the list of valid fields. You may add it be calling `addValidFields(array('$key'));` on this object.");
    return parent::offsetGet($key);
  }
  public function offsetSet($key, $val) {
    if (array_search($key, $this->validFields) === false) throw new UnknownFieldException("The field `$key` is not in the list of valid fields. You may add it be calling `addValidFields(array('$key'));` on this object.");
    return parent::offsetSet($key, $val);
  }
}


