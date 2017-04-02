<?php
namespace Modules\Test\Front\Models;

class Message {

    /**
     *  test function
     *
     * @return void
     */
   public function getMsg()
    {
        $msg='This is the test module default message.<br/> It is called from <b>modules/test/front/controllers/Main.php</b> and message is received from <b>modules/test/front/models/Message.php</b>';
        return $msg;
    }    

}