<header class="app-header app-header-dark">
        <!-- .top-bar -->
        <div class="top-bar">
          <!-- .top-bar-brand -->
          <div class="top-bar-brand">
            <!-- toggle aside menu -->
            <button class="hamburger hamburger-squeeze mr-2" type="button" data-toggle="aside-menu" aria-label="toggle aside menu"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> <!-- /toggle aside menu -->
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>assets/images/logo/logo.png" height='40' alt="">
            </a>
          </div><!-- /.top-bar-brand -->
          <!-- .top-bar-list -->
          <div class="top-bar-list">
            <!-- .top-bar-item -->
            <div class="top-bar-item px-2 d-md-none d-lg-none d-xl-none">
              <!-- toggle menu -->
              <button class="hamburger hamburger-squeeze" type="button" data-toggle="aside" aria-label="toggle menu"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> <!-- /toggle menu -->
            </div><!-- /.top-bar-item -->
            <!-- .top-bar-item -->
       
            <div class="top-bar-item top-bar-item-full">
              <!-- .top-bar-search -->
              <h5 class=' w-100 text-light my-1 '>RAMAAS Electronic & Cosemtics Center</h5>
            </div><!-- /.top-bar-item -->
            <!-- .top-bar-item -->
            <div class="top-bar-item top-bar-item-right px-0 d-none d-sm-flex">
              <!-- .nav -->
              <ul class="header-nav nav">

                <!-- .nav-item -->
                <li class="nav-item ">
                  <!-- <a class="nav-link btn btn-outline-primary" href="<?= BASE_URL ?>sales/pos" ><strong> <h5> <i class="fa fa-plus"></i> POS</h5> </strong> </a> .dropdown-menu -->
                  
                </li><!-- /.nav-item -->


                <div class="nav-item ">
                  <?php $currLang = $_SESSION['lang'] ?? 'so'; ?>
                  <select id="langSwitcher" class="form-control bg-info text-light" style="border:0;" onchange="window.location='<?= BASE_URL ?>set-language.php?lang='+this.value;">
                    <option value="so" <?= ($currLang==='so'?'selected':'') ?>>Soomaali</option>
                    <option value="en" <?= ($currLang==='en'?'selected':'') ?>>English</option>
                  </select>
                </div>

                

                <!-- .nav-item -->
                <li class="nav-item dropdown header-nav-dropdown ">
                  <a class="nav-link" data-toggle="skin" href="#"   ><span class="fas fa-moon ml-1"></span></a> <!-- .dropdown-menu -->
                  
                </li><!-- /.nav-item -->

             
              </ul><!-- /.nav -->
              <!-- .btn-account -->
              <div class="dropdown d-none d-md-flex">
                <button class="btn-account" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="user-avatar user-avatar-md"><img src="<?= BASE_URL ?>assets/images/<?php if(empty($_SESSION['profile'])){echo 'profile.png';}else{ echo $_SESSION['profile'];}  ?>" alt=""></span> <span class="account-summary pr-lg-4 d-none d-lg-block"><span class="account-name"><?=$_SESSION['fname'] ?></span> <span class="account-description"><?php echo get_role(); ?></span></span></button> <!-- .dropdown-menu -->
                <div class="dropdown-menu">
                  <div class="dropdown-arrow d-lg-none" x-arrow=""></div>
                  <div class="dropdown-arrow ml-3 d-none d-lg-block"></div>
                  <h6 class="dropdown-header d-none d-md-block d-lg-none"> <?= $_SESSION['fname'] ?> </h6>
                  <a class="dropdown-item" href="<?= BASE_URL ?>user-profile"><span class="dropdown-icon oi oi-person"></span> <?= __t('Profile') ?></a>
                  <a class="dropdown-item" href="<?= BASE_URL ?>logout"><span class="dropdown-icon oi oi-account-logout"></span> <?= __t('Logout') ?></a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?= BASE_URL ?>chatbot/index.php"><?= __t('Help Center') ?></a>  
                </div><!-- /.dropdown-menu -->
              </div><!-- /.btn-account -->
            </div><!-- /.top-bar-item -->
          </div><!-- /.top-bar-list -->
        </div><!-- /.top-bar -->
      </header><!-- /.app-header -->
  <?php 

    function get_role(){
      global $con; 
      $id = $_SESSION['uid'];
      $stmt = "SELECT * FROM usergroup WHERE group_id in (SELECT usergroup FROM users WHERE userid = $id)";
      $result = mysqli_query($con, $stmt);
      $row = mysqli_fetch_assoc($result);

      echo $row['group_name'];
      
    }
  
  
  ?>
