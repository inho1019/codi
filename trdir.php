<?php 
    if(isset($_POST['file'])) {
        if($_POST['re'] == 'Recovery') {
            for ($i = 0;$i < count($_POST['file']);$i++) {
                rename('trash/'.$_POST['file'][$i],
                'data/'.$_POST['file'][$i]);
            }
            header('Location: index.php?mode=trash');
        } else {
            for ($i = 0;$i < count($_POST['file']);$i++) {
                unlink('trash/'.$_POST['file'][$i]);
            }
            header('Location: index.php?mode=trash');
        }
    } else {
        header('Location: index.php?mode=trash&error=0');
    }
?>