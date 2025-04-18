<?php

// Equipment class
class M_Equipment
{
    use Model;

    protected $table = 'equipment';

    // List of allowed columns for insertion or updates
    protected $allowedColumns = [
        'equipment_id',
        'name',
        'description',
        'file',
        'purchase_price',
        'purchase_date',
        'purchase_shop',
    ];

    
    // Validate input data
    public function validate($data)
    {
        $this->errors = []; // Reset errors for every validation call

        // Name validation
        if (empty($data['name'])) {
            $this->errors['name'] = "Name is required.";
        }

        // Description validation
        if (empty($data['description'])) {
            $this->errors['description'] = "Equipment content is required.";
        }

        // File validation
        if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
            $this->errors['file'] = "Image file is required.";
        } else {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->errors['file'] = "File upload failed. Please try again.";
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) { // Check if file size exceeds 2MB
                $this->errors['file'] = "The file size must be less than 2MB.";
            }
        }
        // Date validation
        if (empty($data['purchase_date'])) {
            $this->errors['purchase_date'] = "Purchase date is required.";
        }
        if (empty($data['purchase_shop'])) {
            $this->errors['purchase_shop'] = "Purchase shop is required.";
        }

        // Price validation
        if (empty($data['purchase_price'])) {
            $this->errors['purchase_price'] = "Price is required.";
        } elseif (!is_numeric($data['purchase_price']) || $data['purchase_price'] <= 0) { // Ensure price is numeric and positive
            $this->errors['purchase_price'] = "Price must be a positive number.";
        }

        // Return true if no errors; otherwise, false
        return empty($this->errors);
    }

    // Method to get errors after validation
    public function getErrors()
    {
        return $this->errors;
    }
}
