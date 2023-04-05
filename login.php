<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's up today?</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&family=Montserrat:wght@300;400;500&family=Gugi&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="./css/initial.min.css">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>

    <div id="main">
        <header id="header">
            <ul id="gnb">
                <li class="liClick">
                    <a href="#HOME" class="aClick"><span class="material-symbols-outlined spanClick">Home</span>Home</a>
                </li>
                <li>
                    <a href="#MYLISTS"><span class="material-symbols-outlined spanClick">check_box</span>MYLISTS</a>
                </li>
                <li>
                    <a href="#LISTDETAIL"><span class="material-symbols-outlined">view_timeline</span>LISTDETAIL</a>
                </li>
                <li>
                    <a href="#CALENDAR"><span class="material-symbols-outlined">calendar_month</span>CALENDAR</a>
                </li>
            </ul>

        </header>
        <div id="HOME">
            <section class="login">
                <?php
                // 제출된 폼 데이터를 처리하는 부분
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $mem_ID = $_POST["mem_ID"];
                    $pass_no = $_POST["pass_no"];

                    // DB 연결 정보
                    $servername = "localhost";
                    $db_mem_ID = "root";
                    $db_pass_no = "root506";
                    $dbname = "todolist";

                    try {
                        // PDO 객체 생성
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_mem_ID, $db_pass_no);

                        // PDO 예외 처리 모드 설정
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // 쿼리 실행
                        $stmt = $conn->prepare("SELECT * FROM member_inf WHERE mem_ID=:mem_ID AND pass_no=:pass_no");
                        $stmt->bindParam(":mem_ID", $mem_ID);
                        $stmt->bindParam(":pass_no", $pass_no);
                        $stmt->execute();

                        // 결과 확인
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $_SESSION['mem_ID'] = $result["mem_ID"];
                            echo "<div class='success'>Welcome, {$_SESSION['mem_ID']}! You have successfully logged in.</div><div class='box'><p>Time is of the essence</p><p>Work smarter, not harder</p><p>Prioritize your tasks</p></div><div class='success'>with your task manager Team NUMBER ONE</div><a href='/login.php'>Logout</a>";
                        } else {
                            $login_error = "Login failed. <br> Invalid ID or password.";
                            echo "<div class='error box'>$login_error</div><a href='/login.php'>Return</a>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }

                    // 연결 종료
                    $conn = null;
                } else {
                    // 아직 폼이 제출되지 않았음
                    echo "
                        <form method='post' action='login.php'>
                        
                        <label for='mem_ID'>ID:</label>
                        <input type='text' id='mem_ID' name='mem_ID' required>
                        <br>
                        <label for='pass_no'>Password:</label>
                        <input type='pass_no' id='pass_no' name='pass_no' required>

                        <button type='submit'>Login</button>
                        </form>";
                }
                ?>
            </section>
        </div>


        <div id="MYLISTS" class="hidden">

        </div>

        <div id="LISTDETAIL" class="hidden">


        </div>

        <div id="CALENDAR" class="hidden">
            <div class="calendar_container">
                    <?php
                    // DB 연결 설정
                    $servername = "localhost";
                    $db_mem_ID = "root";
                    $db_pass_no = "root506";
                    $dbname = "todolist";

                    $conn = new mysqli($servername, $db_mem_ID, $db_pass_no, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // todolist 테이블에서 데이터를 가져옴
                    $sql = "SELECT * FROM todolist";
                    $result = $conn->query($sql);

                    // 현재 날짜 구하기
                    $today = date('Y-m-d');

                    // 달력에 출력할 날짜 구하기
                    $year = date('Y');
                    $month = date('m');
                    $daycircle = date('d');
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    echo "<div class='calendar-header'><div class='month'>$month</div></div>";
                    echo "<div class='calendar-body'>";

                    // 달력 출력
                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        // 현재 날짜와 비교하여 오늘인 경우 circle 클래스 추가
                        $class = ($today == $daycircle) ? "circle" : "";

                        // todolist 테이블에서 해당 날짜의 데이터가 있는지 확인
                        $sql = "SELECT inf.mem_no, inf.mem_ID, li.head
                                FROM member_inf AS inf
                                    INNER JOIN todolist as li
                                    ON inf.mem_no = li.mem_no
                                WHERE inf.mem_no = 1
                                    and from_date <= '$year-$month-$i'
                                    AND to_date >= '$year-$month-$i'";
                        $result = $conn->query($sql);
                        $data = $result->fetch_assoc();

                        // 

                        // 해당 날짜의 배경색과 글자색 결정
                        $bgcolor = "";
                        $textcolor = "";
                        if ($data) {
                            $bgcolor = "#6886c5";
                            $textcolor = "#fff";
                        }

                        // $head = $data['li.head'];

                        // 해당 날짜 출력
                        // $head = $data['head']
                        // echo "<div class='calendar-date $class' style='background-color: $bgcolor; color: $textcolor;'>$i<br>$head</div>";
                        
                        echo "<div class='calendar-date $class' style='background-color: $bgcolor; color: $textcolor;'>$i<br>$data</div>";
                        
                    }
                        echo "</div>";
                    // DB 연결 종료
                    $conn->close();
                    ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        const pages = document.querySelectorAll('#main>div');
        const gnbBtns = document.querySelectorAll('#gnb>li');
    
        for (let i = 0; i < gnbBtns.length; i++) {
            gnbBtns[i].addEventListener("click", function() {
                for (let j = 0; j < pages.length; j++) {
                    if (pages[j].hidden === false) {
                        pages[j].classList.add('hidden');
                        gnbBtns[j].classList.remove('liClick');
                        gnbBtns[j].querySelector('a').classList.remove('aClick');
                        gnbBtns[j].querySelector('span').classList.remove('spanClick');
                    }
                }
                pages[i].classList.toggle('hidden');
                gnbBtns[i].classList.add('liClick');
                gnbBtns[i].querySelector('a').classList.add('aClick');
                gnbBtns[i].querySelector('span').classList.add('spanClick');
            })
        }
    </script>
</body>


</html>