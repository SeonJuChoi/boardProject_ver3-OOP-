<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<div class="container">
    <h2>수정 페이지</h2>
    <form action="index.php" method="post" id="writeForm">
        <table id="editTable">
            <?php
            while ($boardData = $data->fetch_assoc()) {
            echo "<tr>";
            echo "<td>제목 : </td>";
            echo "<td><input type='text' class='form-control' name='title' id='title' value=".$boardData['subject'].">";
            echo "<td><input type='hidden' name='page' value=$page></td>";
            echo "<td><input type='hidden' name='board_id' value=$boardId></td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>작성자 : </td>";
            $writer = $_SESSION['user_alias'];
            echo "<td><input type='text' class='form-control' name='writer' id='writer' value='$writer' readonly></td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>내용 : </td>";
            echo "<td><textarea name='contents' class='form-control' rows='10' id='contents'>".$boardData['contents']."</textarea></td>";
            echo "</tr>";
            }
            ?>

        </table>
        <input type="submit" id="update" value="수정하기" class='btn btn-info' name="bt">
        <input type="button" id="cancel" value="취소" class='btn btn-default' onclick=location.href='index.php';>
    </form>
</div>

</body>
</html>