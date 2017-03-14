<?php
namespace Skel;

class StringTemplate implements Interfaces\Template {
  protected $templatePath;
  protected $templateStr;
  protected $delim = '@@';

  public function __construct(string $template, bool $isFile=true) {
    if ($isFile){
      if (!file_exists($template)) throw new NonexistentFileException("File `$template` doesn't exist!");
      $this->templatePath = $template;
    } else {
      $this->templateStr = $template;
    }
    $this->escapeDelim();
  }

  public function render(array $elmts) {
    $result = $this->getTemplateStr();
    $vars = array();

    $regex = $this->getSubRegex();
    while(preg_match($regex, $result, $vars)) $result = str_replace($vars[0], (string)$elmts[$vars[1]], $result);

    return $result;
  }

  public static function renderInto(string $template, array $elmts, bool $isFile=true) {
    $t = new static($template, $isFile);
    return $t->render($elmts);
  }

  public function __toString() {
    return $this->getTemplateStr();
  }

  public function setDelim(string $delim) {
    $this->delim = $delim;
    $this->escapeDelim();
  }

  protected function getSubRegex() {
    $cap = ($this->delim[0] == '/') ? '#' : '/';
    return $cap.$this->delim.'([a-zA-Z0-9_-]+?)'.$this->delim.$cap;
  }

  protected function escapeDelim() {
    $raw = array('[', ']', '{', '}', '*', '?', '+','$','^');
    $escaped = array('\\[', '\\]', '\\{', '\\}', '\\*', '\\?', '\\+','\\$','\\^');
    $this->delim = str_replace($escaped, $raw, $this->delim);
    $this->delim = str_replace($raw, $escaped, $this->delim);
  }

  protected function getTemplateStr() {
    if ($this->templateStr === null) $this->templateStr = file_get_contents($this->templatePath);
    return $this->templateStr;
  }
}


