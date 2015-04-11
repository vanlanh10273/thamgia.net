<?php
    class User extends AppModel{
        function GetRandomUsers($max){
            $sql = "SELECT id, fullname, avatar_url, email
                    FROM users
                    ORDER BY RAND( )
                    LIMIT " . $max;
            return $this->query($sql);
        }        
    }
?>
