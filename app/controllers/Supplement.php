<?php

class Supplement extends Controller
{
    public function create_supplement()
    {
        $errors = []; 
    $data = $_POST; 
    $supplement_id = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $supplementModel = new M_Supplements;
        $supplementPurchaseModel = new M_SupplementPurchases;

        // Handle file upload if exists and if changed
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "assets/images/Supplement/";
            $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
            $targetFile = $targetDir . $fileName;

            // Validate the file (e.g., check file type and size) and move it to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $data['file'] = $fileName; // Save the new filename for the database
            } else {
                $errors['file'] = "Failed to upload the file. Please try again.";
            }
        }

        if (!isset($data['file'])) {
            $data['file'] = null;
        }

        // --- Validations ---

        // Validate 'name'
        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors['name'] = "Supplement name is required and must be at least 2 characters.";
        }

        // Validate 'purchase_date'
        if (empty($data['purchase_date'])) {
            $errors['purchase_date'] = "Purchase date is required.";
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['purchase_date'])) {
            $errors['purchase_date'] = "Purchase date format must be YYYY-MM-DD.";
        }

        // Validate 'purchase_price'
        if (empty($data['purchase_price'])) {
            $errors['purchase_price'] = "Purchase price is required.";
        } elseif (!is_numeric($data['purchase_price']) || $data['purchase_price'] <= 0) {
            $errors['purchase_price'] = "Purchase price must be a positive number.";
        }

        // Validate 'quantity'
        if (empty($data['quantity'])) {
            $errors['quantity'] = "Quantity is required.";
        } elseif (!is_numeric($data['quantity']) || intval($data['quantity']) <= 0) {
            $errors['quantity'] = "Quantity must be a positive whole number.";
        }

        // Validate 'purchase_shop'
        if (empty($data['purchase_shop']) || strlen($data['purchase_shop']) < 2) {
            $errors['purchase_shop'] = "Purchase shop is required and must be at least 2 characters.";
        }

        // If no errors, save
        if (empty($errors)) {
            $data['quantity_available'] = $data['quantity'];
            $data['quantity_sold'] = 0;

            // Insert into supplements
            $supplementModel->insert($data);
            $supplement_id = $supplementModel->getLastInsertId();

            if ($supplement_id) {
                // Insert into supplement_purchases
                $purchaseData = [
                    'supplement_id' => $supplement_id,
                    'purchase_date' => $data['purchase_date'],
                    'purchase_price' => $data['purchase_price'],
                    'quantity' => $data['quantity'],
                    'purchase_shop' => $data['purchase_shop'],
                ];

                $purchase_result = $supplementPurchaseModel->insert($purchaseData);

                if ($purchase_result) {
                    redirect('manager/supplements'); // Success
                    exit;
                } else {
                    $errors['purchase'] = "Failed to create supplement purchase. Please try again.";
                }
            } else {
                $errors['general'] = "Failed to create supplement. Please try again.";
            }
        }
    }

    // If there were errors or no POST, load the view again with errors
    $this->view('manager/manager-createSupplement', [
        'errors' => $errors
    ]);
    }

    public function deletePurchase($purchase_id)
    {
        $supplementPurchaseModel = new M_SupplementPurchases;
        $supplementModel = new M_Supplements;

        // Step 1: Get the purchase details
        $purchase = $supplementPurchaseModel->getPurchase($purchase_id);
        if (!$purchase) {
            echo "Purchase not found.";
            return;
        }

        // Step 2: Get the supplement ID and current quantity
        $supplement_id = $supplementPurchaseModel->getSupplementId($purchase_id);
        $current_quantity = (int) $supplementModel->getAvailableQuantity($supplement_id);

        // Step 3: Calculate new quantity
        $new_quantity = $current_quantity - (int) $purchase->quantity;
        if ($new_quantity < 0) {
            $new_quantity = 0; // Prevent negative stock
        }

        // Step 4: Delete the purchase record
        $delete_result = $supplementPurchaseModel->delete($purchase_id, 's_purchaseID');

        if (!$delete_result) {
            // Step 5: Update the supplement's available quantity
            $update_result = $supplementModel->update($supplement_id, ['quantity_available' => $new_quantity], 'supplement_id');

            if (!$update_result) {
                redirect('manager/supplement_view/' . $supplement_id);
                exit;
            } else {
                echo "Failed to update supplement quantity.";
            }
        } else {
            echo "Failed to delete the purchase. Please try again.";
        }
    }


    public function deleteSupplement($supplement_id)
    {
        $supplementModel = new M_Supplements;
        $delete = $supplementModel->delete($supplement_id, 'supplement_id');
        // Delete the supplement record
        if (!$delete) {
            redirect('manager/supplements'); // Redirect to a success page
            exit;
        } else {
            // Handle error if deletion fails
            echo "Failed to delete the supplement. Please try again.";
        }
    }

    public function createPurchase()
    {
        $errors = [];
        $data = $_POST;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supplementPurchaseModel = new M_SupplementPurchases;
            $supplementModel = new M_Supplements;

            // Validate required fields
            if (empty($data['purchase_date'])) {
                $errors['purchase_date'] = "Purchase date is required.";
            }

            if (empty($data['purchase_price'])) {
                $errors['purchase_price'] = "Purchase price is required.";
            }

            if (empty($data['quantity'])) {
                $errors['quantity'] = "Quantity is required.";
            }

            // Proceed only if no validation errors
            if (empty($errors)) {
                $supplement_id = (int) $data['supplement_id'];
                $quantity_new = (int) $data['quantity'];

                // Get current available quantity
                $quantity_available = (int) $supplementModel->getAvailableQuantity($supplement_id);

                // Update available quantity
                $quantity_updated = $quantity_available + $quantity_new;

                // Update the supplement's available quantity
                $update_result = $supplementModel->update($supplement_id, ['quantity_available' => $quantity_updated], 'supplement_id');

                if (!$update_result) {
                    // Insert the purchase record
                    $purchase_result = $supplementPurchaseModel->insert($data);

                    if ($purchase_result) {
                        redirect('manager/supplement_view/'.$supplement_id);
                        exit;
                    } else {
                        $errors['purchase'] = "Failed to create supplement purchase. Please try again.";
                    }
                } else {
                    $errors['update'] = "Failed to update supplement quantity.";
                }
            }
            
            $this->view('manager/manager-viewSupplement', [
                'errors' => $errors,
                'data' => $data
            ]);
        }
    }


    public function addSupplementSale()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            $data = $_POST;

            $supplementSaleModel = new M_SupplementSales;
            $supplementModel = new M_Supplements;
            
            $supplement_id = $data['supplement_id'] ?? null;
            $supplement_name = isset($data['name']) ? trim($data['name']) : null;
            $member_id = $data['member_id'] ?? null;

            // Sanitize and validate inputs
            $data['quantity'] = htmlspecialchars(trim($data['quantity']));
            $data['price_of_a_supplement'] = htmlspecialchars(trim($data['price_of_a_supplement']));
            $data['sale_date'] = htmlspecialchars(trim($data['sale_date']));

            // Validate supplement name
            if (empty($supplement_name)) {
                $errors['name'] = "Supplement name is required.";
            } else {
                // Check if supplement exists
                $supplement = $supplementModel->getSupplementByName($supplement_name);
                if (!$supplement) {
                    $errors['name'] = "No supplement found with the given name.";
                } else {
                    // Supplement exists, update supplement_id in $data
                    $data['supplement_id'] = $supplement->supplement_id;
                }
            }

            // Validate quantity
            if (empty($data['quantity'])) {
                $errors['quantity'] = "Quantity is required.";
            }else {
                // First, fetch the available quantity from the supplement table using the supplement_id
                $supplement = $supplementModel->getSupplementById($data['supplement_id']); 
            
                if ($supplement) {
                    if ($data['quantity'] > $supplement->quantity_available) {
                        $errors['quantity'] = "Quantity cannot be greater than available stock ({$supplement->quantity_available}).";
                    }
                } else {
                    $errors['quantity'] = "Selected supplement not found.";
                }
            }

            // Validate price
            if (empty($data['price_of_a_supplement'])) {
                $errors['price_of_a_supplement'] = "Price of a supplement is required.";
            }

            // Validate sale date
            if (empty($data['sale_date'])) {
                $errors['sale_date'] = "Sale date is required.";
            }

            // If no errors, proceed to insert
            if (empty($errors)) {
                $insertStatus = $supplementSaleModel->insert($data);

                if ($insertStatus) {
                    // Update supplement quantity
                    $supplement = $supplementModel->getSupplement($supplement_id);
                    $quantity_sold = (int) $supplement->quantity_sold + (int) $data['quantity'];
                    $quantity_available = (int) $supplement->quantity_available - (int) $data['quantity'];
                    $supplementModel->update($supplement->supplement_id, [
                        'quantity_sold' => $quantity_sold,
                        'quantity_available' => $quantity_available
                    ], 'supplement_id');

                    // Notification
                    $message = "Dear Member, you have successfully purchased " . $data['name'] . " Supplement.";
                    $notificationModel = new M_Notification;
                    $notificationModel->createNotification($member_id, $message, 'Member');

                    $_SESSION['success'] = "Supplement sale added successfully!";
                    redirect('receptionist/members/memberSupplements?id=' . $member_id);
                    exit;
                } else {
                    $_SESSION['error'] = "An error occurred while adding the supplement sale. Please try again.";
                    redirect('receptionist/members/memberSupplements?id=' . $member_id);
                    exit;
                }
            } else {
                // Validation failed
                $_SESSION['form_errors'] = $errors; // store errors in session
                $_SESSION['old_data'] = $data; // store old input in session
                redirect('receptionist/members/memberSupplements?id=' . $member_id);
                exit;
            }
        }
        // If not POST, redirect or show form
        else {
            redirect('receptionist/members');
            exit;
        }
    }

    


    public function getSupplements() {
        try {
            // Check if the query parameter is present
            if (isset($_GET['query'])) {
                $query = $_GET['query'];
                $supplementModel = new M_Supplements;
                $supplements = $supplementModel->searchSupplements($query);
                
                if ($supplements) {
                    echo json_encode($supplements);  // Return valid JSON
                } else {
                    // Return an empty array if no supplements are found
                    echo json_encode([]);
                }
            } else {
                throw new Exception("Query parameter is missing");
            }
        } catch (Exception $e) {
            // Return an error message as JSON
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function deleteSupplementSale()
    {
        $supplementSaleModel = new M_SupplementSales;

        // Get the sale_id from the request
        $sale_id = $_POST['sale_id'] ?? null;
        $member_id = $_POST['member_id'] ?? null;
        if (!$sale_id) {
            echo "Sale ID is required.";
            return;
        }
        $delete_result = $supplementSaleModel->delete($sale_id, 'sale_id');
        // Delete the supplement sale record
        if (!$delete_result) {
            redirect('receptionist/members/supplementRecords?id=' . $member_id);  // Redirect to a success page
            exit;
        } else {
            // Handle error if deletion fails
            echo "Failed to delete the supplement sale. Please try again.";
        }
    }
    
    

}

?>
