<?php

/**
 * Search a text in array texts
 *
 * @param  String name
 * @param  Array names
 * @return Boolean
 */
if (!function_exists('icommerceusps_strposa')) {

  function icommerceusps_strposa($name, $works, $offset = 0)
  {

      if (!is_array($works))
          $works = array($works);
      /*
      foreach($works as $query) {
          if(stripos($name, $query, $offset) !== false)
              return true;
      }
      */

      foreach ($works as $work) {
          if ($name == $work)
              return true;
      }

      return false;
  }

}