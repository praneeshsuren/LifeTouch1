<?php

class Report extends Controller
{
    use Database;
    public function __construct()
    {
        // Check if the user is logged in as a manager
        $this->checkAuth('manager');
    }
    public function index()
    {
        $this->view('manager/manager_dashboard');
    }
    public function findAll()
    {
        
        $sql = "SELECT s.*, e.name AS equipment_name 
                FROM service AS s  
                LEFT JOIN equipment AS e ON s.equipment_id = e.equipment_id";
    
        return $this->query($sql);
    }
    
    public function equipment_report()
    {
        $service = new M_Service();
        $data = [
            'services' => $service->findAll()
        ];
    
        $this->view('manager/equipment_report', $data);
    }

    public function equipment_upcoming_services()
{
    $service = new M_Service();

    // Get today's date
    $today = date('Y-m-d');

    // Fetch only upcoming services (you’ll write this method in M_Service)
    $upcomingServices = $service->getUpcomingServices($today);

    $data = [
        'services' => $upcomingServices
    ];

    $this->view('manager/equipment_upcoming_services', $data);
}
public function equipment_overdue_services()
{
    $service = new M_Service();

    // Get today's date
    $today = date('Y-m-d');

    // Fetch only upcoming services (you’ll write this method in M_Service)
    $upcomingServices = $service->getOverdueServices($today);

    $data = [
        'services' => $upcomingServices
    ];

    $this->view('manager/equipment_overdue_services', $data);
}

    

    
}
