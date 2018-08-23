<?php

include 'model/board_Model.php';

class boardControllers {

    public $model;

    function __construct()
    {
        $this->model = new boardModel();
    }

    function boardIndex() {

        $this->model->getPage();

        if(!isset($_GET['board_id'])) {
            $boardList = $this->model->getBoardList();

            $boardIdArr = $this->model->getBoardComment();

            $page = $this->model->getPageLink();

            include "view/board_List.php";
        }

        else {
            $boardContents = $this->model->getBoardContents();

            include "view/articleRead.php";
        }

    }

    function boardWrite($title, $contents) {
        $writeResult = $this->model->writeArticle($title, $contents);

        if($writeResult)
            echo "<script>alert('등록되었습니다.')</script>";
        echo "<script>location.href='index.php'</script>";

    }

    function login($userId, $userPw) {
        $this->model->login($userId, $userPw);
    }

    function logout() {
        $this->model->logout();
    }

    function deleteArticle($boardId) {
       $deleteResult =  $this->model->deleteArticle($boardId);

       if($deleteResult)
           echo "<script>alert('삭제되었습니다.')</script>";

        echo "<script>location.href='index.php'</script>";
    }

    function updateLoad($page, $boardId) {
        $data =  $this->model->getBoardContents();

        $page = $_POST['page'];
        $boardId = $_POST['board_id'];

        include 'view/update.php';
    }

    function updateArticle($title, $contents, $boardId, $page) {
        $updateResult = $this->model->update($title, $contents, $boardId);
        $link = "index.php?page=".$page."&board_id=".$boardId;

        if($updateResult)
            echo "<script>alert('수정되었습니다.')</script>";

       echo "<script>location.href='$link'</script>";


    }

}


?>