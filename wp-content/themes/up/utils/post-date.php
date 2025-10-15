<?php

class PostDate {
  const FULL = 'full';
  const MEDIUM = 'medium';
  const SHORT = 'short';

   public static $formats = [
     self::FULL => [
       'en' => 'F j, Y',
       'pt-br' => 'd \d\e F \d\e Y'
     ],
     self::MEDIUM => [
       'en' => 'M d, Y',
       'pt-br' => 'd M Y',
     ],
     self::SHORT => [
       'en' => 'M d',
       'pt-br' => 'd M',
     ],
   ];

  public static function format($post, $format = self::FULL) {
    if(!is_object($post)) {
      $post = get_post($post);
    }

    return get_the_date(self::getFormat($format), $post);
  }

  public static function getFormat($format) {
    return !empty(self::$formats[$format]['pt-br'])
      ? self::$formats[$format]['pt-br']
      : "";
  }
}
