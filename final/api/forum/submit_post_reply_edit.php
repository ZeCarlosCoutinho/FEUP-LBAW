<?php
/**
 * Created by PhpStorm.
 * User: epassos
 * Date: 4/17/17
 * Time: 4:52 PM
 */

include_once '../../config/init.php';
include_once  $BASE_DIR . 'database/forum.php';

$replyId = $_POST['reply_id'];
$replyContent  = $_POST['content'];

$reply = editReply($replyId, $replyContent);

echo $reply;