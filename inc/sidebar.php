<aside class="app-aside app-aside-expand-md app-aside-light">
        <!-- .aside-content -->
        <div class="aside-content">
          <!-- .aside-header -->
          <header class="aside-header d-block d-md-none">
            <!-- .btn-account -->
            <button class="btn-account" type="button" data-toggle="collapse" data-target="#dropdown-aside"><span class="user-avatar user-avatar-lg"><img src="<?= BASE_URL ?>assets/images/<?= $_SESSION['profile'] ?>" alt=""></span> <span class="account-icon"><span class="fa fa-caret-down fa-lg"></span></span> <span class="account-summary"><span class="account-name"><?= $_SESSION['fname'] ?></span> <span class="account-description"><?=  get_role() ?></span></span></button> <!-- /.btn-account -->
            <!-- .dropdown-aside -->
            <div id="dropdown-aside" class="dropdown-aside collapse">
              <!-- dropdown-items -->
              <div class="pb-3">
                <a class="dropdown-item" href="<?= BASE_URL ?>user-profile"><span class="dropdown-icon oi oi-person"></span> Profile</a> <a class="dropdown-item" href="<?= BASE_URL ?>logout"><span class="dropdown-icon oi oi-account-logout"></span> Logout</a>
                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Help Center</a> 
              </div><!-- /dropdown-items -->
            </div><!-- /.dropdown-aside -->
          </header><!-- /.aside-header -->
          <!-- .aside-menu -->
          <div class="aside-menu overflow-hidden">
            <!-- .stacked-menu -->
            <nav id="stacked-menu" class="stacked-menu">
              <!-- .menu -->
              <ul class="menu">
                <!-- .menu-item -->
                <li class="menu-item  <?php if(isset($menu) && $menu == 'Dashboard') {echo 'has-active'; }else{ echo '';} ?>">
                  <a href="<?= BASE_URL ?>" class="menu-link"><span class="menu-icon fas fa-home"></span> <span class="menu-text">Dashboard</span></a>
                </li><!-- /.menu-item -->

                <?php
                
                  $groupid = $_SESSION['usergroup'];
 
                  $stmt = "SELECT * from menu WHERE menu_id in (SELECT menu_id FROM submenu WHERE submenu_id IN(
                  SELECT sub_menu_id FROM previlage WHERE group_id = $groupid)) ORDER BY sort_by ASC";
                  $result = mysqli_query($con, $stmt); 
                  while($row = mysqli_fetch_assoc($result)){
                    $id = $row['menu_id'];
                    $name = $row['menu_name'];
                    $icon = $row['menu_icon'];
                    
                   // if($isactive == $name ){ $isactive = 'has-active'; }else{$isactive = "";}
                    
                   if(isset($menu) && $menu == $name) { $is_active = 'has-active'; } else { $is_active = '';}
                     
                    echo "
                    <li class='menu-item  has-child $is_active'>
                      <a href='#' class='menu-link'><span class='$icon'></span> <span class='menu-text'>$name</span></a> <!-- child menu -->
                      <ul class='menu'>  ";
                   
                        $sql = " SELECT * FROM submenu WHERE submenu_id in (SELECT sub_menu_id FROM previlage WHERE group_id = $groupid) AND menu_id = $id";
                        $res = mysqli_query($con, $sql);
                        while($rows = mysqli_fetch_assoc($res)){
                          $submenu_id = $rows['submenu_id'];
                          $sname = $rows['sub_menu_name'];
                         // if($isactive2 == $sname ){ $isactive2 = 'has-active'; }else{$isactive2 = "";}
                          $url = $rows['url'];
                          
                           if(isset($smenu) && $smenu == $sname) { $is_active1 = 'has-active'; } else { $is_active1 = '';}

                          echo "
                          <li class='menu-item $is_active1 '>
                            <a href=' ". BASE_URL . $url ."' class='menu-link'>$sname</a>
                          </li>
                       
                          ";
                        }

                        echo "
                        </ul><!-- /child menu -->
                        </li><!-- /.menu-item -->
                        ";
                    
                   

                  }


                
                ?>

                <!-- .menu-header -->
                <!-- <li class="menu-header">Interfaces </li> -->

              
              
              </ul><!-- /.menu -->
            </nav><!-- /.stacked-menu -->
          </div><!-- /.aside-menu -->
          <!-- Skin changer -->
          <footer class="aside-footer border-top p-2">
            <p href="" class="text-center"> Developed by <strong> <a href="xidigtech.com">XIDIG TECH</a> </strong> </p>
          </footer><!-- /Skin changer -->
        </div><!-- /.aside-content -->
      </aside><!-- /.app-aside -->