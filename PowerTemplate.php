<?php
namespace Skel;

class PowerTemplate implements Interfaces\Template {
  protected $path;

  public function __construct(string $path) { $this->path = $path; }

  public function render(array $elmts) {
    foreach ($elmts as $field => $val) $$field = $val;
    ob_start();
    $success = include $this->path;
    if (!$success) throw new \RuntimeException("Template file `$this->path` not found!");
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
  }

  public static function renderInto(string $template, array $elmts, bool $isFile=true) {
    if (!$isFile) $template = "data://text/plain;base64,".base64_encode($template);
    $t = new static($template);
    return $t->render($elmts);
  }

  public function __toString() {
    return $this->path;
  }
}

