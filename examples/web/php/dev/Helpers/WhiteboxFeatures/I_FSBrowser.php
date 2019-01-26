<?php
/////////////////////////////////////////////////////////////////////////
//Namespace
/////////////////////////////////////////////////////////////////////////
namespace App\Helpers\WhiteboxFeatures;



/////////////////////////////////////////////////////////////////////////
//Imports
/////////////////////////////////////////////////////////////////////////
use IteratorAggregate;



/**An interface used to represent a a filesystem browser (browsing the filesystem)
 * Interface I_FSBrowser
 * @package WhiteBox\Filesystem
 */
interface I_FSBrowser extends IteratorAggregate, I_MagicalArrayable {
    /////////////////////////////////////////////////////////////////////////
    //Magics
    /////////////////////////////////////////////////////////////////////////
    /**Instantiate from a URI
     * I_FSBrowser constructor.
     * @param string $uri being the URI to load from
     */
    public function __construct(string $uri);
}