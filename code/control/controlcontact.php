<?php
include_once("model/modelcontact.php");

class cContact {
    public function addContact($fullname, $email, $phone, $title, $content, $id_user){
            $p = new mContact();
            $kq = $p->insertContact($fullname, $email, $phone, $title, $content, $id_user);
            return $kq;
        }
    public function getAllContact(){
        $p = new mContact();
        $tblContact = $p->selectAllContact();
        if($tblContact == false){
            return -2;
        }else{
            if($tblContact->num_rows>0){
                return $tblContact;
            }else{
                return -1;
            }
        }
    }
    public function updateContactStatus($id_contact, $status) {
        $p = new mContact();
        return $p->updateStatus($id_contact, $status);
    }
}