<?php

    class M_SupplementPurchases{

        use Model;

        protected $table = 'supplement_purchases';

        // List of allowed columns for insertion or updates
        protected $allowedColumns = [
            's_purchaseId',
            'supplement_id',
            'purchase_date',
            'purchase_price',
            'quantity',
            'purchase_shop'
        ];

        public function getSupplementId($purchase_id)
        {
            $query = "SELECT supplement_id FROM $this->table WHERE s_purchaseID = $purchase_id";

            // Execute the query to fetch the last inserted schedule_id
            $result = $this->get_row($query);
            // Return the retrieved schedule_id
            return $result ? $result->supplement_id : null;
        }

        public function getPurchase($purchase_id){
            $query = "SELECT * FROM $this->table WHERE s_purchaseID = $purchase_id";

            // Execute the query to fetch the last inserted schedule_id
            $result = $this->get_row($query);
            // Return the retrieved schedule_id
            return $result ? $result : null;
        }
    }

?>