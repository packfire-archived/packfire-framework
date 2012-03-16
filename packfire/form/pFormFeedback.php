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
     * Read the feedback messge for a particular field and message type
     * @param string $field The field the message is targeted to.
     * @param string $type The type of message
     * @return string Returns the feedback message
     * @since 1.0-sofia
     */
    public function read($field, $type){
        $session = $this->service('session');
        $feedback = $session->get('form.feedback');
        if($feedback == null){
            $feedback = new pMap();
        }
        $message = $feedback->get($field . '-' . $type);
        return $message;
    }
    
    /**
     * Send feedback to the form for a particular field
     * @param string $field The field the message is targeted to.
     * @param string $type The type of message
     * @param string $message The feedback
     * @since 1.0-sofia
     */
    public function feedback($field, $type, $message){
        $session = $this->service('session');
        $feedback = $session->get('form.feedback');
        if($feedback == null){
            $feedback = new pMap();
        }
        $feedback->set($field . '-' . $type, $message);
        $session->set('form.feedback', $feedback);
    }
    
    /**
     * Clear the form feedback
     * @since 1.0-sofia 
     */
    public function clear(){
        $session = $this->service('session');
        $session->remove('form.feedback');
    }
    
}
