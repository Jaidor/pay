<?php
namespace Jideola\Traits;

trait Tx{

    public static function transaction() {
      self::test();
    }

    protected static function test() {
      echo "I am here for test...";
    }
}