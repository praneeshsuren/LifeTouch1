<?php

class Supplement extends Controller
{
    public function create_supplement()
    {
        $errors = []; // Initialize errors array
        $data = $_POST; // Get POST data
        $supplement_id = null;

        // Check if form is submitted via POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supplementModel = new M_Supplements;
            $supplementPurchaseModel = new M_SupplementPurchases;

            // Handle file upload if exists
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "assets/images/Supplement/";
                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                $targetFile = $targetDir . $fileName;

                // Validate the file (e.g., check file type and size) and move it to the target directory
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['file'] = $fileName; // Save the filename for the database
                } else {
                    $errors['file'] = "Failed to upload the file. Please try again.";
                }
            }

            // If no image uploaded, leave the 'file' key as null (if not set)
            if (!isset($data['file'])) {
                $data['file'] = null;
            }

            // Validate required fields (for example, 'name' must be provided)
            if (empty($data['name'])) {
                $errors['name'] = "Supplement name is required.";
            }

            // If there are no errors, save data to the database
            if (empty($errors)) {
                
                $supplementModel->insert($data);
                $supplement_id = $supplementModel->getLastInsertId(); // Get the last inserted supplement ID

                $data['quantity_available'] = $data['quantity'];
                $data['quantity_sold'] = 0;

                // Insert into the supplements table
                $supplementModel->insert($data); // Insert the supplement data

                if ($supplement_id) {
                    // After supplement is inserted, handle supplement purchase data
                    // Add the purchase data (example values here, you can modify them as per your form)
                    $purchaseData = [
                        'supplement_id' => $supplement_id,
                        'purchase_date' => date('Y-m-d'), // Assume today's date for purchase
                        'purchase_price' => $data['purchase_price'], // You should collect purchase price from form
                        'quantity' => $data['quantity'], // You should collect quantity from form
                        'purchase_shop' => $data['purchase_shop'], // You should collect shop info from form
                    ];

                    // Insert into the supplement_purchases table
                    $purchase_result = $supplementPurchaseModel->insert($purchaseData); 

                    // Check if purchase was successfully added
                    if ($purchase_result) {
                        redirect('manager/supplements'); // Redirect to a success page
                        exit;
                    } else {
                        $errors['purchase'] = "Failed to create supplement purchase. Please try again.";
                    }
                } else {
                    $errors['general'] = "Failed to create supplement. Please try again.";
                }
            }
        }

        // Load the form view with errors (if any)
        $this->view('manager/manager-createSupplement', ['errors' => $errors]);
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

        // Delete the supplement record
        if ($supplementModel->delete($supplement_id)) {
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
        }

        $this->view('manager/manager-createSupplement', ['errors' => $errors]);
    }



}

?>
