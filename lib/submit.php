<?php
if(isset($_GET['on'])) {
echo 'test';
$run = $_GET['on'];
if ($run == 1){$data = 13;}
elseif ($run == 2) {$data = 18;}
elseif ($run == 3) {$data = 23;}
elseif ($run == 4) {$data = 17;}
elseif ($run == 5) {$data = 27;}
elseif ($run == 6) {$data = 22;}
elseif ($run == 7) {$data = 10;}
elseif ($run == 8) {$data = 9;}
elseif ($run == 9) {$data = 11;}
elseif ($run == 10) {$data = 19;}
exec("sudo python off.py");
exec("sudo python on.py ".$data);}
if(isset($_GET['off'])) {
$run = $_GET['off'];
if ($run == 1){$data = 13;}
elseif ($run == 2) {$data = 18;}
elseif ($run == 3) {$data = 23;}
elseif ($run == 4) {$data = 17;}
elseif ($run == 5) {$data = 27;}
elseif ($run == 6) {$data = 22;}
elseif ($run == 7) {$data = 10;}
elseif ($run == 8) {$data = 9;}
elseif ($run == 9) {$data = 11;}
elseif ($run == 10) {$data = 19;}
exec("sudo python off.py");}
if(isset($_POST['sysoff'])) {
exec('echo 0 > sys.dat');}
if(isset($_POST['syson'])) {
exec('echo 1 > sys.dat');}
header('Location: ../');
 ?>
