
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/bootstrap.js"></script>
<script>

</script>
<body>
<div class="container">
<div id="loginBox">
    <table>
        <?php
        if (!isset($_SESSION['user_id'])) {
            echo "<td>&nbsp;<input type='button' class='btn btn-info' id='loginBt' value='로그인' onclick=location.href='view/login.html'></td>";
        }
        // 로그인 했을 경우
        else {
        echo "<form method='post' action='index.php'>";
        echo "<td>" . $_SESSION['user_alias'] . "님 환영합니다. &nbsp;</td>";
        echo "<td><input type='hidden' name='logout' value='logout'></td>";
        echo "<td><input type='submit' class='btn btn-info' value='로그아웃' id='logoutBt'></td>";
        echo "</form>";
        }
        ?>
    </table>
</div>
<h2>게시판</h2>
<div id="boardList">
    <table id="boardTable" class="table table-hover">
        <thead>
        <tr>
            <th width="70px">글 번호</th>
            <th width="400px">제목</th>
            <th width="100px">작성자</th>
            <th width="70px">조회수</th>
            <th width="70px">덧글수</th>
            <th width="200px">작성일</th>
        </tr>
        </thead>
        <?
        $boardCommentNum = 0;

        while($row = $boardList->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['board_id']."</td>";
            $link = 'index.php?page='.$page->currentPage."&board_id=".$row['board_id'];
            echo "<td><a href='$link'>".$row['subject'].'</td>';
            echo "<td>".$row['user_name']."</td>";
            echo "<td>".$row['hits']."</td>";
            echo "<td>$boardIdArr[$boardCommentNum]</td>";
            echo "<td>".$row['reg_date']."</td>";
            echo "</tr>";

            $boardCommentNum++;

        }

        ?>
    </table>
</div>
<div class="container">

    <?php

    echo $page->currentPage." ".$page->allPage." ".$page->currentSet."<br>";

    if($page->currentPage != 1)
        echo "<input type='button' class='btn btn-info' value='<<' onclick=location.href='$page->firstLink'>";

    if(($page->currentPage != 1 && $page->allPage <= 10) || $page->currentSet != 1)
        echo "<input type='button' class='btn btn-default' value='<' onclick=location.href='$page->previousLink'>";
    ?>
    <ul class="pagination">
    <?php

    for($pageNum = $page->setStart ; $pageNum <= $page->setEnd ; $pageNum++) {
        $link = $page->pageLink[$pageNum];
        echo "<li><a href=$link id='page'>$pageNum</a></li>";
    }
    ?>
    </ul>
    <?php
        if(($page->currentPage != $page->allPage && $page->allPage <= 10) || $page->allPageSet != $page->currentSet)
            echo "<input type='button' class='btn btn-default' value='>' onclick=location.href='$page->nextLink'>";
        if ($page->currentPage != $page->allPage)
            echo "<input type='button' class='btn btn-info' value='>>' onclick=location.href='$page->lastLink'>";

    ?>
</div>
<div>
    <input type="button" id="writeButton" class="btn btn-default" value="글쓰기" onclick=location.href='view/write.html'>
</div>
</div>

</body>
</html>