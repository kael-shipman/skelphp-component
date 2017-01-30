<?php
namespace Skel;

class StringTemplate implements Interfaces\Template {
  protected $templateStr = '';

  public function __construct(string $template, bool $isFile=true) {
    if ($isFile){
      if (!($this->templateStr = @file_get_contents($template))) {
        throw new NonexistentFileException("File `$template` doesn't exist!");
      }
    } else {
      $this->templateStr = $template;
    }
  }

  public function render(array $elmts) {
    $result = $this->templateStr;
    $vars = array();

    while(preg_match('/##([a-zA-Z0-9_-]+?)##/', $result, $vars)) $result = str_replace($vars[0], (string)$elmts[$vars[1]], $result);

    return $result;
  }

  public static function renderInto(string $template, array $elmts, bool $isFile=true) {
    $t = new static($template, $isFile);
    return $t->render($elmts);
  }

  public function __toString() {
    return $this->templateStr;
  }
}


