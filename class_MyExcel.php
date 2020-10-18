<?php

  require '../vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\Cell\{DefaultValueBinder, DataType};

  class CustomValueBinder extends DefaultValueBinder {
      public static function dataTypeForValue($pValue) { //只重写dataTypeForValue方法，去掉一些不必要的判断
          if (is_null($pValue)) {
              return DataType::TYPE_NULL;
          } elseif ($pValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
              return DataType::TYPE_INLINE;
          } elseif ($pValue[0] === '=' && strlen($pValue) > 1) {
              return DataType::TYPE_FORMULA;
          } elseif (is_bool($pValue)) {
              return DataType::TYPE_BOOL;
          } elseif (is_float($pValue) || is_int($pValue)) {
              return DataType::TYPE_NUMERIC;
          }
          return DataType::TYPE_STRING;
      }



}