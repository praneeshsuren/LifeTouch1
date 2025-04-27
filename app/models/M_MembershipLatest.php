<?php
class M_MembershipLatest
{

    use Model;

    protected $table = 'membership_latest';
    protected $allowedColumns = [
        'id',
        'member_id',
        'plan_id',
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];
    public function getMembershipReport()
    {
        $query = "SELECT 
    m.member_id,
    CONCAT(m.first_name, ' ', m.last_name) AS member_name,
    m.contact_number,
    m.email_address,
    mp.plan AS membership_plan,
    ms.start_date AS membership_start_date,
    mp.amount AS expected_amount,
    ms.end_date AS last_valid_date,
    CASE 
        WHEN ms.end_date >= CURDATE() THEN 'active'
        ELSE 'inactive'
    END AS subscription_status
FROM 
    membership_latest ms
JOIN 
    member m ON ms.member_id = m.member_id
JOIN 
    membership_plan mp ON ms.plan_id = mp.membershipPlan_id
ORDER BY 
    ms.end_date DESC;
";

        return $this->query($query);
    }
}
