<?php 
                $cookiename="loggedin";
                      if(!isset($_COOKIE[$cookiename])) {
                             header("Location: /login.php?url=".$_SERVER['REQUEST_URI']);
                        } else {
            
                        }
 ?> 
<?php 
 exec('git -C ../ fetch --all & git reset --hard origin/master & git pull origin master & git -C ../ fetch --all & git reset --hard origin/master & git pull origin master');
 exec('sudo chmod 777 -R ../');
 header('Location: ../');
 ?>
 
