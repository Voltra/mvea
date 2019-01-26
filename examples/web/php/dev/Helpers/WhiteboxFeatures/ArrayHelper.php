<?php
/////////////////////////////////////////////////////////////////////////
//Namespace
/////////////////////////////////////////////////////////////////////////
namespace App\Helpers\WhiteboxFeatures;



/**A helper class for arrays that gives static helper methods
 * Class ArrayHelper
 * @package WhiteBox\Helpers
 */
abstract class ArrayHelper{
    /////////////////////////////////////////////////////////////////////////
    //Class methods
    /////////////////////////////////////////////////////////////////////////
    public static function is_assoc(array $arr): bool{
        return (bool)is_array($arr) && (bool)array_diff_key($arr,array_keys(array_keys($arr)));
    }
}