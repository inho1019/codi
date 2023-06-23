<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Coding Diary</title>
    <?php
        session_start();
        require('lib/function.php');
    ?>
    <link rel="stylesheet" href="lib/style.css" type="text/css">
</head>
<body>
    <a href="index.php" class="title">Coding Diary</a>
    <?php
            if (!isset($_GET['mode'])) {
                if(isset($_SESSION['activate'])) {
                button('index.php?mode=trash','trbut','Tr');
                }
            } else {
                if ($_GET['mode'] == 'trash') {
                    if(isset($_SESSION['activate'])) {
                        if ($_SESSION['activate']['name'] == 'admin' && $_SESSION['activate']['id'] == 'admin') {
                            button('admin.php','trbut','Ad');
                        }
                    }
                }
            }
            if(isset($_SESSION['activate'])) {
                echo "<div class='user'>
                    <span><b> USER</b></span><span>".$_SESSION['activate']['name']."</span>
                </div>";
            }
    ?>
    <br>
    <?php
        if (isset($_GET['mode'])) {
            if ($_GET['mode'] == 'write') {
                button("index.php","tpbut","Cancel");
            } else if ($_GET['mode'] == 'read') {
                button("index.php","tpbut","Back"); echo ' &nbsp ';
                if(isset($_SESSION['activate'])) {
                button("dedir.php?file=".urlencode($_GET['file'])."","tpbut","Delete"); echo ' &nbsp ';
                button("index.php?mode=update&file=".urlencode($_GET['file'])."","tpbut","Modify");
                }
            } else if ($_GET['mode'] == 'update') {
                button("index.php?mode=read&file=".urlencode($_GET['file'])."","tpbut","Back");
            } else if ($_GET['mode'] == 'search') {
                button("index.php","tpbut","Back");
                searchform();
            } else if ($_GET['mode'] == 'trash') {
                button("index.php","tpbut","Back");
            } else if ($_GET['mode'] == 'register') {
                button("index.php","tpbut","Back"); echo ' &nbsp ';
                button("index.php?mode=login","tpbut","Login");
            } else if ($_GET['mode'] == 'login') {
                button("index.php","tpbut","Back"); echo ' &nbsp ';
                button("index.php?mode=register","tpbut","Register");
            }
        } else {
            if(isset($_SESSION['activate'])) {
                button("index.php?mode=write","tpbut","Write"); echo ' &nbsp ';
                button("lodir.php","tpbut","Logout");
            } else {
                button("index.php?mode=login","tpbut","Login");
            }
            if (isset($_GET['sort'])) {
                button("index.php","stbut","Abc");
            } else {
                button("index.php?sort=abc","stbut","Date");
            }
            searchform();
        }
    ?>
    <fieldset style=" 
    <?php
    if (!isset($_GET['mode'])) {
        echo "text-align:center;";
    }
    ?>">
        <legend style="text-align: left;font-weight : bold; font-size: 30px;">
            <?php
                if(isset($_GET['mode'])) {
                    echo strtoupper($_GET['mode']);
                } else {
                    echo "LIST";
                }
            ?>
        </legend>
            <?php
                if(isset($_GET['error'])) {
                    if($_GET['error'] == 0){
                        echo "<div id='warning'>Forbidden : Invalid content</div><br>";
                    } else if ($_GET['error'] == 1) {
                    echo "<div id='warning'>Duplicate Exist</div><br>";
                    }
                } else {
                    null;
                }
            ?>
            <?php
                if(isset($_GET['mode'])) {
                    if($_GET['mode'] == 'write') {
                        if(isset($_SESSION['activate'])) {
                            form("redir.php",null,null,"Post",null,
                            '<input id="filebut" type="file" name="file" accept=".png, .jpg, .jpeg, .gif">');
                        }
                    } else if ($_GET['mode'] == 'read') {
                        read();
                    } else if ($_GET['mode'] == 'update') {
                        if(isset($_SESSION['activate'])) {
                            form("updir.php",urldecode($_GET['file']),urldecode(
                            file_get_contents('data/'.$_GET['file'])),"Modify",$_GET['file'],null);
                        }
                    } else if ($_GET['mode'] == 'search') {
                        findsys();
                    } else if ($_GET['mode'] == 'trash') {
                        if(isset($_SESSION['activate'])) {
                            trash();
                        }
                    } else if ($_GET['mode'] == 'register') {
                        loreform('indir.php',"<li>NICKNAME<br><input type='text' name='nickname' 
                        pattern='^[a-zA-Zㄱ-힣0-9]*$' maxlength='16' minlength='4' required>
                        </li>",'REGISTER','required');
                    } else if ($_GET['mode'] == 'login') {
                        loreform('lodir.php',null,'LOGIN',null);
                    }
                } else {
                    abc();
                }
            ?>
    </fieldset>
</body>
</html>