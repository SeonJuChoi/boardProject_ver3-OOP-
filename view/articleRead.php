<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/bootstrap.js"></script>
<body>

<div class="container">
    <h2>게시글 보기</h2>
    <!-- 게시글 테이블 -->
    <table class="table" id="articleTable">
        <?
        // <-- 게시물 출력
        while($board = $boardContents->fetch_assoc()) {
            // <-- 제목
            echo "<tr>";
            echo "<thead>";
            echo "<th colspan='5' class='active' id='title'>제목 : " . $board['subject'] . "</th>";
            echo "</thead>";
            echo "</tr>";

            echo "<tr>";
            // <-- 글 번호
            echo "<td>글번호 : " . $board['board_id'] . "</td>";
            // <-- 작성자
            echo "<td>작성자 : " . $board['user_name'] . "</td>";
            // <-- 작성일
            echo "<td>작성일 : " . $board['reg_date'] . "</td>";
            // <-- 조회수
            echo "<td>조회수 : " . $board['hits'] . "</td>";
            // <-- 덧글수
            echo "<td>덧글수 : " . '0' . "</td>";
            echo "</tr>";
            // <-- 게시글 내용
            echo "<tr>";
            $contents = nl2br($board['contents']);
            echo "<td colspan='5'>$contents</td>";
            echo "</tr>";


            echo "<div id='menuBox'>";

            $page = $_GET['page'];
            $listLink = "index.php?page=" . $page;
            $boardId = $board['board_id'];
            $userId = $board['user_id'];
        }

        echo "</table>";
            echo "<form action='index.php' method='post'>";
            // <-- 목록 버튼 생성
            echo "<input type='button' id='listButton' class='btn btn-info' value='목록' onclick=location.href='$listLink'>";

            // <-- 자기가 쓴 글이 아닐 경우 수정, 삭제 X
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_id'] == $userId) {
                    echo "<input type='hidden' name='page' value=$page>";
                    echo "<input type='hidden' name='board_id' value=$boardId>";
                    // <-- 수정 버튼 생성
                    echo "<input type='submit' id='rewriteButton' class='btn btn-default' name='bt' value='수정'>";
                    // <-- 삭제 버튼 생성
                    echo "<input type='submit' id='deleteButton' name='bt' class='btn btn-default' value='삭제'>";
                    echo "</form>";
                }
        }
        echo "</div>";
    ?>
</div>
</body>
</html>