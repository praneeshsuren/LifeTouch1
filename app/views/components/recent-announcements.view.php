<h2>Recent Announcements</h2>
<div class="announcements">
    <?php if (!empty($data['announcements'])): ?>
        <?php foreach ($data['announcements'] as $announcement): ?>
            <div class="announcement">
                <div class="profile-img">
                    <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                </div>
                <div class="message">
                    <p>
                        <b><?php echo $announcement->created_by; ?></b></br>
                        <?php echo $announcement->subject; ?></br>
                        <span class="description-text">
                            <?php echo $announcement->description; ?>
                        </span>
                    </p>
                    <small 
                        class="text-muted time-ago"
                        data-created-date="<?php echo $announcement->created_date; ?>"
                        data-created-time="<?php echo $announcement->created_time; ?>">
                    </small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recent announcements found.</p>
    <?php endif; ?>
</div>
