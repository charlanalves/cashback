<?php

class Iugu_Account extends APIResource {

  public static function create($attributes=Array()) { return self::createAPI($attributes); }
  public static function fetch($key)                  { return self::fetchAPI($key); }
  public        function save()                      { return $this->saveAPI(); }
  public        function delete()                    { return $this->deleteAPI(); }

  public        function refresh()                   { return $this->refreshAPI(); }
  public static function search($options=Array())    { return self::searchAPI($options); }
  
  
public static function configuration($attributes=Array()) 
{
    $response = 
      self::API()->request(
        "POST",
        static::url($attributes).'/configuration',
        $attributes
    );

    return $response;
  }
  
public static function requestVerification($attributes=Array(), $idAccount) 
{
    $response = 
      self::API()->request(
        "POST",
        static::url($attributes).'/'.$idAccount.'/request_verification',
        $attributes
    );

    return $response;
  }

}
