<?php
if($userdata['image_url']==""){
$img_url="img/ellipse15.png";
}else{
$img_url=$userdata['image_url'];
}
?>
                  <div class="navbar-right shadow">
            <ul class="navbar-right__menu">
        
               <li class="nav-notification">
                  <div class="dropdown-custom">
                     <a href="javascript:;" class="nav-item-toggle icon-active">
                        <img class="svg" src="img/svg/alarm.svg" alt="img">
                     </a>
                     <div class="dropdown-parent-wrapper">
                        <div class="dropdown-wrapper">
                           <h2 class="dropdown-wrapper__title">Notifications <span class="badge-circle badge-warning ms-1">4</span></h2>
                           <ul>
                              <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                 <div class="nav-notification__type nav-notification__type--primary">
                                    <img class="svg" src="img/svg/inbox.svg" alt="inbox">
                                 </div>
                                 <div class="nav-notification__details">
                                    <p>
                                       <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                       <span>sent you a message</span>
                                    </p>
                                    <p>
                                       <span class="time-posted">5 hours ago</span>
                                    </p>
                                 </div>
                              </li>
                              <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                 <div class="nav-notification__type nav-notification__type--secondary">
                                    <img class="svg" src="img/svg/upload.svg" alt="upload">
                                 </div>
                                 <div class="nav-notification__details">
                                    <p>
                                       <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                       <span>sent you a message</span>
                                    </p>
                                    <p>
                                       <span class="time-posted">5 hours ago</span>
                                    </p>
                                 </div>
                              </li>
                              <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                 <div class="nav-notification__type nav-notification__type--success">
                                    <img class="svg" src="img/svg/log-in.svg" alt="log-in">
                                 </div>
                                 <div class="nav-notification__details">
                                    <p>
                                       <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                       <span>sent you a message</span>
                                    </p>
                                    <p>
                                       <span class="time-posted">5 hours ago</span>
                                    </p>
                                 </div>
                              </li>
                              <li class="nav-notification__single nav-notification__single d-flex flex-wrap">
                                 <div class="nav-notification__type nav-notification__type--info">
                                    <img class="svg" src="img/svg/at-sign.svg" alt="at-sign">
                                 </div>
                                 <div class="nav-notification__details">
                                    <p>
                                       <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                       <span>sent you a message</span>
                                    </p>
                                    <p>
                                       <span class="time-posted">5 hours ago</span>
                                    </p>
                                 </div>
                              </li>
                              <li class="nav-notification__single nav-notification__single d-flex flex-wrap">
                                 <div class="nav-notification__type nav-notification__type--danger">
                                    <img src="img/svg/heart.svg" alt="heart" class="svg">
                                 </div>
                                 <div class="nav-notification__details">
                                    <p>
                                       <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                       <span>sent you a message</span>
                                    </p>
                                    <p>
                                       <span class="time-posted">5 hours ago</span>
                                    </p>
                                 </div>
                              </li>
                           </ul>
                           <a href="" class="dropdown-wrapper__more">See all incoming activity</a>
                        </div>
                     </div>
                  </div>
               </li>
               <!-- ends: .nav-notification -->
                  <li class="nav-flag-select">
                  <div class="dropdown-custom">
                     <a href="javascript:;" class="nav-item-toggle"><img src="img/flag.png" alt="" class="rounded-circle"></a>
                     <div class="dropdown-parent-wrapper">
                        <div class="dropdown-wrapper dropdown-wrapper--small">
                           <a href=""><img src="img/eng.png" alt=""> English</a>
                          </div>
                     </div>

                  </div>
               </li>
               <!-- ends: .nav-flag-select -->
               <li class="nav-author">
                  <div class="dropdown-custom">
                     <a href="javascript:;" class="nav-item-toggle"><img src="<?php echo $img_url; ?>" alt="" class="rounded-circle">
                        <span class="nav-item__title">Danial<i class="las la-angle-down nav-item__arrow"></i></span>
                     </a>
                     <div class="dropdown-parent-wrapper">
                        <div class="dropdown-wrapper">
                           <div class="nav-author__info">
                              <div class="author-img">
                                 <img src="<?php echo $img_url; ?>" alt="" class="rounded-circle">
                              </div>
                              <div>
                                 <h6><?php echo $userdata['name'];?></h6>
                                 <span><?php echo $userdata['email'];?></span>
                              </div>
                           </div>
                           <div class="nav-author__options">
                              <ul>
                                 <li id="profile" class="">
                                    <a href="profile">
                                       <i class="fa fa-user"></i> Profile</a>
                                 </li>
                                 <?php
                                 if($userdata['type']=="admin"){
                                 ?>
                                    <li>
                                    <a href="radiumsahil_admin/dashboard">
                                       <i class="fa fa-user-graduate"></i> Admin Login</a>
                                 </li>
                                <?php
                                }
                                ?> 
                        </ul>
                              <a href="logout" class="nav-author__signout">
                                 <i class="uil uil-sign-out-alt"></i> Sign Out</a>
                           </div>
                        </div>
                        <!-- ends: .dropdown-wrapper -->
                     </div>
                  </div>
               </li>
               <!-- ends: .nav-author -->
            </ul>
            <!-- ends: .navbar-right__menu -->
            
            <div class="navbar-right__mobileAction d-md-none">
               <a href="#" class="btn-author-action">
                  <img class="svg" src="img/svg/more-vertical.svg" alt="more-vertical"></a>
            </div>
         </div>
         <!-- ends: .navbar-right -->
      </nav>
