<?php

class boardModel {
    const DBADDR = 'localhost';
    const DBUSER = 'user';
    const DBPASSWD = 'pass';
    const DBNAME = 'board';
    const DBTABLE = 'board_list';
    private $dbConn;
    private $pageObj;

    function __construct()  {
        $this->dbConn = new mysqli(self::DBADDR, self::DBUSER,
                    self::DBPASSWD, self::DBNAME);

       $this->pageObj = new Pagenation();
    }

    function getBoardList() {
        $query = "select * from ".self::DBTABLE." where board_pid = 0 order by reg_date desc limit ".
        $this->pageObj->numOfStart.", ".Pagenation::NUMOFCONTENTS;
        echo $query."<br>";

        $result = $this->dbConn->query($query);

        return $result;

    }

    function getBoardComment() {
        $boardIdList = array();
        $boardIdIdx = 0;

        $query = "select board_id from ".self::DBTABLE." where board_pid = 0 order by reg_date desc";

        $result = $this->dbConn->query($query);

        while ($value = $result->fetch_assoc()) {

            $boardId = $value['board_id'];

            $query = "select * from ".self::DBTABLE." where board_pid = ".$boardId;

            $commentResult = $this->dbConn->query($query);
            $boardIdList[$boardIdIdx] = $commentResult->num_rows;
            $boardIdIdx++;

        }

        return $boardIdList;
    }

    function getBoardContents() {
        if(isset($_GET['board_id']))
            $boardId = $_GET['board_id'];
        else if(isset($_POST['board_id'])) {
            $boardId = $_POST['board_id'];
        }

        $query = "select * from ".self::DBTABLE." where board_id = ".$boardId;

        $result = $this->dbConn->query($query);

        return $result;
    }

    function getPage() {
        if (!isset($_GET['page']))
            $this->pageObj->currentPage = 1;
        else
            $this->pageObj->currentPage = $_GET['page'];

        $this->pageObj->numOfStart = ($this->pageObj->currentPage - 1) * Pagenation::NUMOFCONTENTS;
    }

    function getPageLink() {

        $query = "select * from ".self::DBTABLE." where board_pid = 0 order by reg_date desc";

        $result = $this->dbConn->query($query);

        $allColCount = $result->num_rows;
        // 전체 페이지
        $this->pageObj->allPage = ceil($allColCount / Pagenation::NUMOFCONTENTS);
        // 페이지 셋 설정
        $this->pageObj->allPageSet = ceil($this->pageObj->allPage / Pagenation::PAGESET);
        $this->pageObj->currentSet = ceil($this->pageObj->currentPage / Pagenation::PAGESET);
        // 페이지 셋 시작, 끝 설정
        $this->pageObj->setStart = (($this->pageObj->currentSet - 1) * Pagenation::PAGESET) + 1;
        $this->pageObj->setEnd = (($this->pageObj->currentSet - 1) * Pagenation::PAGESET) + Pagenation::PAGESET;

        if($this->pageObj->setEnd > $this->pageObj->allPage)
            $this->pageObj->setEnd = $this->pageObj->allPage;

        // 10페이지 이하일 경우 이전 다음 페이지 설정
        if ($this->pageObj->allPage <= 10) {
            $this->pageObj->previousPage = $this->pageObj->currentPage - 1;
            $this->pageObj->nextPage = $this->pageObj->currentPage + 1;
        }
        // 10페이지 이상일 경우 이전 다음 페이지 설정
        else {
            $this->pageObj->previousPage = $this->pageObj->setStart - 1;
            $this->pageObj->nextPage = $this->pageObj->setStart + Pagenation::PAGESET;
        }

        // URL 설정
        $this->pageObj->firstLink = Pagenation::BASEURL."1";
        $this->pageObj->lastLink = Pagenation::BASEURL.$this->pageObj->allPage;
        $this->pageObj->previousLink = Pagenation::BASEURL.$this->pageObj->previousPage;
        $this->pageObj->nextLink = Pagenation::BASEURL.$this->pageObj->nextPage;

        for($page = $this->pageObj->setStart ; $page <= $this->pageObj->setEnd ; $page++) {
            $this->pageObj->pageLink[$page] = Pagenation::BASEURL.$page;
        }

        return $this->pageObj;
    }

    function writeArticle($title, $content) {
        $date = date("Y-m-d H:i:s");
        $userId = $_SESSION['user_id'];
        $userName = $_SESSION['user_alias'];

        $title = htmlspecialchars($title, ENT_QUOTES);
        $content = htmlspecialchars($content, ENT_QUOTES);
        $content = str_replace(" ", "&nbsp;", $content);


        $query = "insert into ".self::DBTABLE." (subject, contents, user_id, user_name, reg_date) values (".
                "'".$title."',"."'".$content."',"."'".$userId."', "."'".$userName."', "."'".$date."')";

        $result = $this->dbConn->query($query);

        return $result;


    }

    function deleteArticle($boardId) {
        $query = "delete from ".self::DBTABLE." where board_id=".$boardId;
        $result = $this->dbConn->query($query);

        return $result;

    }

    function update($title, $contents, $boardId) {
        $title = htmlspecialchars($title, ENT_QUOTES);
        $contents = htmlspecialchars($contents, ENT_QUOTES);
        $contents = str_replace(" ", "&nbsp;", $contents);

        $query = "update ".self::DBTABLE." set subject='".$title."', contents='".$contents."' where board_id=".$boardId;
        echo $query;

        $result = $this->dbConn->query($query);

       return $result;
    }

    function login($inputId, $inputPw) {
        $query = "select user_id, user_pw, user_alias  from user where user_id='".$inputId."'";
        $loginResult = $this->dbConn->query($query);

        $loginRow = $loginResult->fetch_assoc();

        $id = $loginRow['user_id'];
        $pw = $loginRow['user_pw'];
        $alias = $loginRow['user_alias'];

        if($id == null || $pw != $inputPw) {
            echo "<script>alert('로그인 실패!')</script>";
        }
        else {
            if(!isset($_SESSION))
                session_start();

            $_SESSION['user_id'] = $id;
            $_SESSION['user_pw'] = $pw;
            $_SESSION['user_alias'] = $alias;
        }

    }

    function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_pw']);
        unset($_SESSION['user_alias']);

        session_destroy();
    }


}
// insert, select, delete, update
class Pagenation {
    const NUMOFCONTENTS = 5;
    const PAGESET = 10;
    const BASEURL = 'index.php?page=';

    public $currentPage;
    public $numOfStart;
    public $allPage;
    public $previousPage;
    public $nextPage;

    public $allPageSet;
    public $currentSet;
    public $setStart;
    public $setEnd;

    public $firstLink;
    public $lastLink;
    public $pageLink = array();
    public $previousLink;
    public $nextLink;

}
?>