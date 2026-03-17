   <main class="main-content" >

      <div class="sidebar-wrapper" >
         <div class="sidebar sidebar-collapse" id="sidebar">
            <div class="sidebar__menu-group">
               <ul class="sidebar_nav">
                <li class="menu-title mt-30">
                     <span>Dashboard</span>
                  </li> 
             <li >
                     <a  href="dashboard" id="dashboard" class="active">
                        <span class="nav-icon fa fa-home"></span>
                        <span class="menu-text">Dashboard</span>
                      </a>
                  </li>
                      <?php
                                 if($userdata['type']=="admin"){
                                 ?>
                 <li>
                     <a href="radiumsahil_admin/dashboard" class="">
                        <span class="nav-icon fa fa-user-cog"></span>
                        <span class="menu-text">Admin Dashboard</span>
                      </a>
                  </li>
                  <?php
                  }
                  ?>
                  <li class="menu-title mt-30">
                     <span>SERVICE</span>
                  </li>
                  <li>
                     <a href="buy-number" id="buy-number" class="">
                        <span class="nav-icon fa fa-sim-card"></span>
                        <span class="menu-text">Buy Number</span>
                     </a>
                  </li>
                  <li>
                     <a href="recharge" id="recharge" class="">
                        <span class="nav-icon fa fa-receipt"></span>
                        <span class="menu-text">Recharge</span>
                    </a>
                  </li>     
                  <li class="menu-title mt-30">
                     <span>HISTORY</span>
                  </li>
                 <li>
                     <a id="numbers-history" href="numbers" class="">
                        <span class="nav-icon fa fa-sms"></span>
                        <span class="menu-text">Number</span>
                    </a>
                  </li>
                   <li>
                     <a id="transactions" href= "transactions" class="">
                        <span class="nav-icon fa fa-history"></span>
                        <span class="menu-text">Transaction</span>
                    </a>
                  </li>    
                  <li class="menu-title mt-30">
                     <span>Developer</span>
                  </li>
                     <li>
                     <a id="api-tool" href="api-tool" class="">
                        <span class="nav-icon fa fa-database"></span>
                        <span class="menu-text">Api Tool</span>
                    </a>
                  </li>    
                  <li class="menu-title mt-30">
                     <span>SUPPORT</span>
                  </li>
                          <li>
                     <a href="faq" id="faq" class="">
                        <span class="nav-icon uil fa fa-question-circle"></span>
                        <span class="menu-text">FAQ</span>
                     </a>
                  </li>
                  <li class="">
                     <a href="<?php echo $site_data['support_url'];?>" target="_blank">
                        <span class="nav-icon fa fa-headset"></span>
                        <span class="menu-text">Support</span>
                     </a>
                  </li>
                 <br><br>
               </ul>
            </div>
         </div>
      </div>
