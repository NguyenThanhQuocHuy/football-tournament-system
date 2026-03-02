<?php
include_once("modelconnect.php");

class mContact {
    public function insertContact($fullname, $email, $phone, $title, $content, $id_user){
        $p = new mConnect();
            $conn = $p->moketnoi();
            if($conn){
                $strquery = "INSERT INTO contact (fullname, email, phone, title, content, id_user, status, created_at)
            VALUES ('$fullname', '$email', '$phone', '$title', '$content', '$id_user', 'Chờ phản hồi', NOW())";
                $tblcontact = $conn->query($strquery);
                $p->dongketnoi($conn);
                return $tblcontact;
            }else{
                return false;
            }
    }
    public function selectAllContact(){
        $p = new mConnect();
            $conn = $p->moketnoi();
            if($conn){
                $strquery = "SELECT id_contact, fullname, email, phone, title, content, id_user, status, created_at
                FROM contact
                WHERE status = 'Chờ phản hồi'
                ORDER BY id_contact DESC;";
                $tblContact = $conn->query($strquery);
                $p->dongketnoi($conn);
                return $tblContact;
            }else{
                return false;
            }
    }
    public function updateStatus($id_contact, $status){
        $p = new mConnect();
        $conn = $p->moketnoi();
        if($conn){
            $query = "UPDATE contact SET status = '$status' WHERE id_contact = $id_contact";
            $result = $conn->query($query);
            $p->dongketnoi($conn);
            return $result;
        }
        return false;
    }
}