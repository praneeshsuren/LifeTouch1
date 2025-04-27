<?php
    class MembershipPlan extends Controller
    {
        public function getMembershipPlanCount()
        {
            $membershipModel = new M_MembershipSubscriptions;
            $membershipPlans = $membershipModel->countMembershipPlans();

            header('Content-Type: application/json');
            echo json_encode([
                'membershipPlans' => $membershipPlans
            ]);

        }
    }
?>