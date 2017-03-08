<?php
namespace Skel;

class ComponentTemplate extends \Skel\StringTemplate {
  protected $langParser;
  protected $delim = '@@';

  public function setLangParser(\Skel\Interfaces\LangParser $p) {
    $this->langParser = $p;
  }

  public function render(array $elmts) {
    if (!array_key_exists('component', $elmts) || !($elmts['component'] instanceof \Skel\Interfaces\Component)) throw new \InvalidArgumentException("You must include a reference to a valid `Component` object among the elements that you pass into `render`");
    if (!$this->langParser) throw new UnpreparedObjectException("`ComponentTemplate` objects must be given a `LangParser` instance in order to render. You must pass a valid `LangParser` instance to this object via `setLangParser` BEFORE attempting to render.");

    $result = $this->templateStr;
    $vars = array();

    $regex = $this->getSubRegex();
    while(preg_match($regex, $result, $vars)) {
      // If it's a method....
      if ($vars[2]) {
        $method = substr($vars[1], 0, strlen($vars[1])-strlen($vars[2]));
        $args = $this->langParser->getContainedArgs($vars[2])[0];
        if (!method_exists($elmts['component'], $method)) throw new \RuntimeException("You've attempted to call the method `$method`, which does not exist on the Component you've passed in for render");
        $val = call_user_func_array(array($elmts['component'], $method), $args);
      } else {
        $val = (string)$elmts[$vars[1]];
      }

      $result = str_replace($vars[0], $val, $result);
    }

    return $result;
  }

  protected function getSubRegex() {
    $cap = ($this->delim[0] == '/') ? '#' : '/';
    return $cap.$this->delim.'([a-zA-Z0-9_-]+(\\(.+\\))?)'.$this->delim.$cap;
  }

  public function setVarDelimiter(string $delim) {
    $this->delim = $delim;
    $this->escapeDelim();
  }
}

