<?php
namespace Skel;

class TestComponent extends Component {
  public function testMethod(int $times, string $str) {
    $i = 0;
    $result = '';
    while ($i++ < $times) {
      $result .= $str;
    }
    return $result;
  }
}

