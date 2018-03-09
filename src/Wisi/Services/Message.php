<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 22/02/18
 * Time: 15:02
 */

namespace Wisi\Services;


use Wisi\Model\MessageModel;

class Message extends MessageModel
{

    protected $MSGID;
    protected $MSGTYPE;
    protected $MSGTEXT;
    protected $MESSAGESTP;
    protected $FROMUSER;
    protected $FROMJOB;
    protected $MSGTEXT1;


    /**
     * Messages constructor.
     * @param array $aSystemInfos
     * @throws \Exception
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * @return array
     */
    public function getMessagesQSYSOPR()
    {
        $aMessages = parent::selectMessagesQSYSOPR();

        if (is_array($aMessages) && count($aMessages) > 0) {
            foreach ($aMessages as $k => $aMessage) {
                $aMessages[$k] = array_map('trim', $aMessage);
            }
        }

        return $aMessages;
    }
}