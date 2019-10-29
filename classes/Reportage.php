<?php


interface ReportageInterface
{
  public function highestNumberOfReports(string $field);
  public function descendingTree(string $name);
  public function userTreeReverse(string $name);
}


class Reportage implements ReportageInterface
{
  private $reportage = [];

  /**
   * Reportage constructor.
   * @param array $array - [['user'=>'string', 'reportsTo'=>'string']]
   */
  public function __construct(array $array = null)
  {
    // With initiation of Object we check $array if it exists and it is array to avoid non-array values.
    // If something goes wrong, we keep empty array as our working variable $reportage.
    if(isset($array) || is_array($array)) {

      // Now we have to check array and clean it from bad data if exists.
      // foreach ($array as $key=>$value) {
        // each array item must consist of valid 'user' and 'reportsTo'.
        // If  'user' or 'reportsTo' are null, false, doesn't exist or something else - it means 'wrong entry data'

          //if(!isset($value['user'], $value['reportsTo']) || !is_string($value['user']) || !is_string($value['reportsTo']) ) {

          // We can remove it at all as an option.
          // unset($array[$key]);

          // or we can stringify values to avoid problems.
          // $array[$key]['user'] = (string)$value['user'];
          // $array[$key]['reportsTo'] = (string)$value['reportsTo'];
        // }
      // }

      // Now we have array to work with.
      $this->reportage = $array;
    }
  }

  /**
   * Returns array with users who received or sent highest number of reports
   * @param string $field
   * @return array
   */
  public function highestNumberOfReports(string $field ='reportsTo'):array
  {
    // By default $field will be 'reportsTo'

    // But it can be something else so I made verification. A bit primitive.
    if ($field !=='user' || $field !=='reportsTo') {
      $field = 'reportsTo';
    }

    // Get our array
    $reportage = $this->reportage;
    // Check if it has correct format
    if(!isset($field) || !is_string($field)) {
      return [];
    }

    // Check if it has data inside
    if(self::is_countable_function($reportage)) { // can be replaced by is_countable() in PHP 7.3

      // I make new array with only values of given field in $field.
      $arrayColumns = array_column($reportage, $field);

      // As I removed boolean check in __construct() of an Object, I make checking here.
      $arrayColumns = array_filter($arrayColumns, function($key) {
        return ($key===true || $key===false) ? false : true;
      }, 0);
      // After I count how many times evey user name was repeated. It will be ['name1'=>1, 'name2'=>4]
      $countNames = array_count_values($arrayColumns);

      // After I find the highest number in given array. It will be number of repeats = sent reports for every user.
      $userMaxIndex = max(array_values($countNames));

      // Now we can search for users from $countNames with highest number of sent or received reports
      $outputArray = array_filter($countNames, function($key) use ($userMaxIndex) {
        return $key == $userMaxIndex ? true : false;
      }, 0);

      // In the end I remove number of repeats from output array and return only array with names.
      return array_keys($outputArray);
    }
    // In case array is empty
    return [];
  }

  /**
   * Returns the whole tree in descending order as an array above the given user $name
   * @param string $name
   * @return array
   */
  public function descendingTree(string $name):array
  {
    // Get our array
    $reportage = $this->reportage;

    // First we find index in array where our user is indicated.
    $indexOfUser = array_search($name, array_column($reportage, 'user'));

    // If our user exists in array we will receive index.
    if($indexOfUser!==false) {
      // We delete everything after our index in array.
      array_splice($reportage, $indexOfUser);
    }
    // In return I simplify array to ['user','user'] view with help of array_column().
    // And after I reverse all data for descending order.
    return array_reverse(array_column($reportage, 'user'));
  }

  public function userTreeReverse(string $name = null):array
  {
    $outputArray = [];
    // $name should be string. Otherwise return empty array
    if(is_string($name)) {
      // Initiate recursive function to search and collect our users
      // And array_reverse() helps us to reverse array values to get data according task requirements.
      $outputArray = array_reverse(self::iterable_search($name));
    }
    return $outputArray;
  }

  /**
   * Helping function checks if array has elements inside.
   * @param $var
   * @return bool
   */
  protected function is_countable_function($var): bool
  {
    return (is_array($var) ||  is_iterable($var) || $var instanceof Countable);
  }

  /**
   * Iterable function helps to collect all names from 'reportsTo' field and go deeper for next iteration where  next'user' field will be equal to current 'reportsTo' field.
   * @param string $name
   * @return array
   */
  protected function iterable_search(string $name):array
  {
    $reportage = $this->reportage;
    $tempArray = [];

    // We look for index of field with given name.
    $indexOfUser = array_search($name, array_column($reportage, 'user'));

    // to proceed we have to be sure that we have index and also 'reportsTo' value isn't false boolean
    if($indexOfUser!==false && $reportage[$indexOfUser]['reportsTo']!==false) {
      $tempArray[] = $reportage[$indexOfUser]['reportsTo'];  // we save our current name.
      // and here is the start of recursive function with merging array process. We get new array elements and merge it with current.
      // process from the depth of recursive. All the way back we merge and merge user names in array till we get the first initiated iteration which returns our ready array
      return array_merge($tempArray, self::iterable_search($reportage[$indexOfUser]['reportsTo']));
    }
    return []; // otherwise we return empty array without initiating new recursion.
  }

}