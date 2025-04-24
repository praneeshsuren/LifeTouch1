<h2>Recent Announcements</h2>
<div class="announcements">
    <?php if (!empty($data['announcements'])): ?>
        <?php foreach ($data['announcements'] as $announcement): ?>
            <div class="announcement">
                <div class="profile-img">
                    <img src="<?php echo URLROOT; ?>/assets/images/Admin/<?php echo !empty($announcement->image) ? $announcement->image : 'default-placeholder.jpg'; ?>" alt="Admin Profile Picture">
                </div>
                <div class="message">
                    <p>
                        <b><?php echo $announcement->admin_name; ?></b></br>
                        <?php echo $announcement->subject; ?></br>
                    </p>
                    <div class="time-ago">
                        <p><i class="ph ph-calendar"></i><?php echo $announcement->created_date; ?></p>
                        <p><i class="ph ph-clock"></i><?php echo $announcement->created_time; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recent announcements found.</p>
    <?php endif; ?>
</div>
