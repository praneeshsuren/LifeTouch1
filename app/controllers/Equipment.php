<?php

    class Equipment extends Controller{

        public function api(){

            $equipmentModel = new M_Equipment;
            $equipment = $equipmentModel->findAll();

            header('Content-Type: application/json');
            echo json_encode($equipment);
            exit;

        }
    }

?>