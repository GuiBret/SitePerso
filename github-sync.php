<!DOCTYPE html>

<?php
`git pull && php ../../bin/console assetic:dump && php ../../bin/console cache:clear --env=prod && composer install`;

echo "Connexion réussie";

?>
