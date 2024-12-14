<?php
echo "Hash para 'admin': " . password_hash('admin', PASSWORD_BCRYPT) . "<br>";
echo "Hash para 'estudiante': " . password_hash('estudiante', PASSWORD_BCRYPT) . "<br>";
?>
