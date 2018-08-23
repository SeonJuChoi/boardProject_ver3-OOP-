<?php
session_start();

include 'controllers/board_Controllers.php';

$obj = new boardControllers();

if(isset($_POST['bt'])) {
    if($_POST['bt'] == '삭제')
        $obj->deleteArticle($_POST['board_id']);
    else if($_POST['bt'] == '수정'){
        $page = $_POST['page'];
        $boardId = $_POST['board_id'];
        $obj->updateLoad($page, $boardId);
    }
    else if($_POST['bt'] == '수정하기'){
        $obj->updateArticle($_POST['title'], $_POST['contents'], $_POST['board_id'], $_POST['page']);
    }

}
else if(isset($_POST['write'])) {
    $obj->boardWrite($_POST['title'], $_POST['contents']);
}

else {
    if(isset($_POST['id']))
        $obj->login($_POST['id'],$_POST['pw']);
    else if(isset($_POST['logout']))
        $obj->logout();

    $obj->boardIndex();
}

// connsf~~~~~~~`
?>