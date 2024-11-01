<?php if(isset($_SESSION['e_msg']) && $_SESSION['e_msg']){ ?>

<div class="update-nag">Success : <?php echo esc_html($_SESSION['e_msg']); ?></div>

<br/>

<?php unset($_SESSION['e_msg']); } ?>

<?php if(isset($_SESSION['error_message']) && $_SESSION['error_message']){ ?>

<div class="update-nag error-message"><?php echo esc_html($_SESSION['error_message']); ?></div>

<br/>

<?php unset($_SESSION['error_message']); } ?>

<div class="wcc-generate-api-box">

  <form action="<?php echo esc_url(admin_url('admin.php?page=wcc-seo-keyword-research')) ?>" method="post" class="wpforms-form">

    <div class="wcc-card">

      <div class="wcc-card-header"><h1 class="wcc-dashboard-title-1">Thank You For Installing<br>WCC SEO Keyword Research</h1></div>

      <div class="wcc-card-body wcc-dashboard-table-setting">

          <h2 class="wcc-card-text wcc-text-center">Please Register to generate API Key.</h2>

          <table  style="width: 100%"> 

            <tbody>

              <tr>

                <td>

                  <label class="form-label">First Name</label>

                  <input type="text" required="required" name="first_name" value="" class="form-control wcc-dashboard-form-control" placeholder="First Name" style='width: 100%'/>

                </td>

                <td>

                  <label class="form-label">Last Name</label>

                  <input type="text" required="required" name="last_name" value="" class="form-control wcc-dashboard-form-control" placeholder="Last Name" style='width: 100%'/>

                </td>

              </tr>

              <tr>

                <td>

                  <label class="form-label">Email</label>

                  <input type="email" required="required" name="email" value="" class="form-control wcc-dashboard-form-control" placeholder="Email" style='width: 100%'/>

                </td>

                <td>

                  <label class="form-label">Conatct No</label>

                  <input type="text" required="required" name="contact_no" value="" class="form-control wcc-dashboard-form-control" placeholder="Conatct No" style='width: 100%'/>

                </td>

              </tr>

            </tbody>

          </table>

          <div class="wcc-text-center wcc-mt-40">

            <input type="submit" name="generate_api" value="Generate Key - Free" title="Generate Key - Free" class="wcc-dashboard-btn">

          </div>

      </div>

    </div>

  </form>

</div>