<?php if(isset($_SESSION['e_msg']) && $_SESSION['e_msg']){ ?>

<div class="update-nag">Success : <?php echo esc_html($_SESSION['e_msg']); ?></div>

<br/>

<?php unset($_SESSION['e_msg']); } ?>

<?php if(isset($_SESSION['error_message']) && $_SESSION['error_message']){ ?>

<div class="update-nag error-message"><?php echo esc_html($_SESSION['error_message']); ?></div>

<br/>

<?php unset($_SESSION['error_message']); } ?>

<div class="wcc-generate-api-box wcc-text-center">

  <form action="<?php echo esc_url(admin_url('?page=wcc-seo-keyword-research')) ?>" method="post" class="wpforms-form">

    <div class="wcc-card">

      <div class="wcc-card-header"><h1 class="wcc-dashboard-title-1">Thank You</h1></div>

      <div class="wcc-card-body">

          <h3 class="wcc-card-title">WCC SEO Keyword Research</h3>

          <p class="wcc-card-text">Your key is generated and its ready to start.</p>

          <a class="wcc-dashboard-btn" href="<?php echo esc_url(admin_url('admin.php?page=wcc-seo-keyword-research')) ?>">Get Started</a>

      </div>

    </div>

  </form>

</div>