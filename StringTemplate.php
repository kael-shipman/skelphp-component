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

    // Using a string function instead of regexes for performance reasons
    //while(preg_match('/##(.+?)##/', $result, $vars)) $result = str_replace($vars[0], (string)$elmts[$vars[1]], $result);
    $offset = 0;
    while (($startvar = strpos($result, '##', $offset)) !== false) {
      $endvar = strpos($result, '##', $startvar+2);
      $var = substr($result, $startvar+2, $endvar-($startvar+2));
      $result = str_replace("##$var##", (string)$elmts[$var], $result);

      // Set offset to beginning of replaced string so that we can catch any new variables inserted
      $offset = $startvar;
    }

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


