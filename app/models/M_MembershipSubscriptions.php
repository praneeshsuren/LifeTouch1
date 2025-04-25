<?php
class M_MembershipSubscriptions
{

    use Model;

    protected $table = 'membership_subscription';
    protected $allowedColumns = [
        'id',
        'member_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'created_at',
        'updated_at'
    ];
    public function getMembershipReport()
    {
        $query = "SELECT 
            m.member_id,
            CONCAT(m.first_name, ' ', m.last_name) as member_name,
            m.contact_number,
            m.email_address,
            mp.plan as membership_plan,
            ms.start_date as membership_start_date,
            mp.amount as expected_amount,
            ms.end_date as last_valid_date,
            ms.status as subscription_status
          FROM 
            membership_subscription ms
          JOIN 
            member m ON ms.member_id = m.member_id
          JOIN 
            membership_plan mp ON ms.plan_id = mp.membershipPlan_id
          ORDER BY 
            ms.end_date DESC";

        return $this->query($query);
    }
    public function countMembershipPlans()
    {
        // Query to join the membership_subscription table with the membership_plan table
        // and count the number of subscriptions for each membership plan
        $query = "
                SELECT p.plan, COUNT(s.id) AS count
                FROM $this->table s
                JOIN membership_plan p ON s.plan_id = p.membershipPlan_id
                GROUP BY p.plan
            ";

        $membershipPlans = $this->query($query);

        if ($membershipPlans) {
            return $membershipPlans;
        } else {
            return [];
        }
    }

    public function findByMemberId($memberId)
    {
        $query = "SELECT ms.*, mp.plan FROM 
                    $this->table ms 
                    JOIN membership_plan mp ON ms.plan_id = mp.membershipPlan_id 
                    WHERE ms.member_id = :member_id";

        $params = [
            'member_id' => $memberId
        ];
        $result = $this->query($query, $params);
        if ($result) {
            return $result[0]; // Return the first result
        } else {
            return null; // No result found
        }
    }
}
