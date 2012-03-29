<?php
pload('packfire.ioc.pBucketUser');
pload('packfire.collection.pMap');

/**
 * Form feedback provider service.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.form
 * @since 1.0-sofia
 */
class pFormFeedback extends pBucketUser {
    
    /**
     * Read the feedback for the previous form.
     * Note that feedback is cleared from session when read.
     * @return pMap Returns the collection of feedback 
     * @since 1.0-sofia
     */
    public function read(){
        $session = $this->service('session');
        $feedback = $session->get('form.feedback');
        if($feedback == null){
            $feedback = new pMap();
        }
        $this->clear();
        return $feedback;
    }
    
    /**
     * Set the feedback to session
     * @param pMap $feedback The collection of feedback
     * @since 1.0-sofia
     */
    public function feedback($feedback){
        $session = $this->service('session');
        $session->set('form.feedback', $feedback);
    }
    
    /**
     * Clear the form feedback
     * @since 1.0-sofia 
     */
    public function clear(){
        $this->service('session')->remove('form.feedback');
    }
    
}
