<?php
    function form($link,$val1,$val2,$submit,$hidden) {
        echo '<form action="'.$link.'" method="post">  
                <input type="hidden" value="'.$hidden.'" name="file">
                <label id="title" for="title">TITLE </label>
                <input class="txbox" type="text" name="title" value="'.$val1.'"><br><br>
                <span id="explain">EXPLAIN</span><br>
                <textarea name="explain" rows="10" style="resize:none; font-size:20px;">'.$val2.'</textarea>
                <input class="submit" type="submit" value="'.$submit.'">
            </form>';
    }
    function button($link,$class,$val) {
        echo '<button onclick="location.href=\''.$link.'\'" class="'.$class.'">'.$val.'</button>';
    }
    function read() {
        echo '<div id="reti">
                <div><b>TITLE</b></div><div>'.htmlspecialchars(urldecode($_GET['file'])).'</div>
                </div><br>
                <div id="reex">'.nl2br(htmlspecialchars(urldecode(
                file_get_contents('data/'.$_GET['file'])))).'</div>
                ';
    }
    function abc() {
        $data = scandir('data/');
                    if (isset($_GET['sort'])) {
                    for($i = 0;$i < count($data);$i++) {
                        if($data[$i] != '.' && $data[$i] != '..') {
                            if (mb_strlen(urldecode($data[$i]),'utf-8') > 12) {
                                $rena = mb_substr(urldecode($data[$i]),0,12,'utf-8')."...";
                                echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.htmlspecialchars($rena).'</button>';
                            } else {
                                echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.
                                htmlspecialchars(urldecode($data[$i])).'</button>';
                            }
                        }
                    }
                } else {
                    $timesort = array();
                    for ($i = 0;$i < count($data);$i++) {
                        if($data[$i] != '.' && $data[$i] != '..') {
                            $time = date("Y-m-d H:i:s", filemtime('data/'.$data[$i]));
                            array_push($timesort,$time.$data[$i]);
                        }
                    }
                    rsort($timesort);
                    for ($i = 0;$i < count($timesort);$i++) {
                        $timelen = mb_strlen($timesort[$i],'utf-8');
                        $timedata = mb_substr($timesort[$i],19,$timelen,'utf-8');
                        if (mb_strlen(urldecode($timedata),'utf-8') > 13) {
                            $rena = mb_substr(urldecode($timedata),0,13,'utf-8')."...";
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($timedata).'\'">'.htmlspecialchars($rena).'</button>';
                        } else {
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($timedata).'\'">'.
                            htmlspecialchars(urldecode($timedata)).'</button>';
                        }
                    }
                }
    }
    function direct($rename,$link) {
        if(!empty($_POST['title']) && !empty($_POST['explain']) && 
        trim($_POST['title']) != '' && trim($_POST['explain']) != '') {
            $rename;
            file_put_contents('data/'.urlencode($_POST['title']),urlencode($_POST['explain']));
            header('Location: index.php');
        } else {
            header('Location: index.php'.$link.'&error=0');
        }
    }
    function findsys() {
        if(isset($_GET['find']) && trim($_GET['find']) != '') {
            $data = scandir('data/');
            $on = 0;
            for ($i = 0; $i < count($data); $i++) {
                if($data[$i] != '.' && $data[$i] != '..') {
                    if (strpos($data[$i],urlencode($_GET['find'])) !== false) {
                        $on = 1;
                        if (mb_strlen(urldecode($data[$i]),'utf-8') > 13) {
                            $rena = mb_substr(urldecode($data[$i]),0,13,'utf-8')."...";
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.htmlspecialchars($rena).'</button>';
                        } else {
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.
                            htmlspecialchars(urldecode($data[$i])).'</button>';
                        }
                    }
                }
            }
            if ($on == 0) {
                echo "<div class='searchtext'>Nothing Found</div>"; 
            } 
        } else {
            echo "<div class='searchtext'>SEARCH IT...</div>";
        }
    }
    function trash() {
        $trash = 0;
        $data = scandir('trash/');
            echo '<form action="trdir.php" method="post">';
            for($i = 0;$i < count($data);$i++) {
                if ($data[$i] != '.' && $data[$i] != '..') {
                $trash = 1;
                echo '<input class="ckbox" type="checkbox" name="file[]" value='.$data[$i].'>'.urldecode($data[$i]).'<br>';
                }
            }
                if ($trash == 0) {
                echo '<div class="searchtext">Nothing Exists</div>';
                } else {
                    echo '<input type="submit" name="re" value="Recovery"> &nbsp; ';
                    if($_SESSION['activate']['id'] == 'admin') {
                        echo'<input type="submit" name="re" value="Remove">';
                    }
                }
        echo '</form>';
                    }
                    
    function searchform() {                
        echo "
                <form class='searchform' action='index.php' method='get'>
                    <input type='hidden' name='mode' value='search'>
                    <input id='scbox' type='text' name='find'>
                    <button id='searchbutton' type='submit'>Search</button>
                </form>
            ";
    }
    function loreform($link,$null,$submit) {
        echo "
            <form class='register' action=$link method='post'>
                <ul class='reli'>
                    $null
                    <li>ID<br><input type='text' name='id' pattern='^[a-zA-Z0-9]*$'></li>
                    <li>PASSWORD<br><input type='password' name='password' pattern='^[a-zA-Z0-9]*$'></li>
                    <li><button type='submit'>$submit</button></li>
                </ul>
            </form>
            ";
    }
?>