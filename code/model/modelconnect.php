<?php
    class mConnect{
        public function moKetNoi(){
            $host = "localhost";
            $username = "root";
            $pass = "";
            $dbname = "tournament_db";
            return mysqli_connect($host,$username,$pass,$dbname);
            
        }
        public function dongKetNoi($conn){
            $conn->close();
        }
        
    }

?>
