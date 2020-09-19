<div id="chat-container" class="fixed" style="display: block;"><!--
| CHAT HEADER SECTION
-->
<h2 class="chat-header">
    <i class="fa fa-comment"></i> 
    <span class="btn btn-xs btn-success" id="current_status">Online</span>
    <a href="javascript:;" class="chat-form-close pull-right"><i class="fa fa-remove"></i></a>
    <span class="dropdown user-dropdown">
    <a href="javascript:;" class="pull-right chat-config" data-toggle="dropdown">
        <i class="fa fa-cog"></i>
    </a>
    <ul class="dropdown-menu">
        <li class="divider"></li>
        <li>
            <a href="javascript: void(0);" id="edit-profile">
              <span class="pull-left">Profile</span>
              <span class="fa fa-user pull-right"></span>
              <span class="clearfix"></span>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="javascript: void(0);" id="change-password">
              <span class="pull-left">Change Password</span>
              <span class="fa fa-lock pull-right"></span>
              <span class="clearfix"></span>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="javascript: void(0);">
              <div class="btn-group btn-toggle status-btn-group"> 
                <button class="btn btn-xs btn-success">Online</button>
                <button class="btn btn-xs btn-default">Offline</button>
              </div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="javascript: void(0);" id="logout">
              <span class="pull-left">Sign Out</span>
              <span class="fa fa-sign-out pull-right"></span>
              <span class="clearfix"></span>
            </a>
        </li>
    </ul>
    </span>
</h2>
<!--
| CHAT CONTACTS LIST SECTION
-->
<div class="chat-inner" id="chat-inner" style="position:relative;">
<div class="chat-group">
 <strong>Friends</strong>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="2" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Neeraj Neeraj</small>
            <span class="badge progress-bar-danger" rel="2"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="3" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Nicholus Andrews</small>
            <span class="badge progress-bar-danger" rel="3"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="4" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Rasleen Kaur</small>
            <span class="badge progress-bar-danger" rel="4"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="5" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Abhishek Bhardwaj</small>
            <span class="badge progress-bar-danger" rel="5"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-online"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="6" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Abhi Bhardwaj</small>
            <span class="badge progress-bar-danger" rel="6"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="7" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Abhishek Bhardwaj</small>
            <span class="badge progress-bar-danger" rel="7"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="8" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Nicholus Andrews</small>
            <span class="badge progress-bar-danger" rel="8"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="9" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/BjIlI3CkS4W0pA31tNMZnGl0jR0lzEik.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Stuart Trusty</small>
            <span class="badge progress-bar-danger" rel="9"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="10" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Gaurav Kumar</small>
            <span class="badge progress-bar-danger" rel="10"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="11" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Akhil Kumar</small>
            <span class="badge progress-bar-danger" rel="11"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="16" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Ava Roth</small>
            <span class="badge progress-bar-danger" rel="16"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-offline"></span>
        </span>
    </div>
    </a>
  
    <a href="javascript: void(0)" data-toggle="popover">
    <div class="contact-wrap">
      <input type="hidden" value="31" name="user_id">
       <div class="contact-profile-img">
           <div class="profile-img">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
       </div>
        <span class="contact-name">
            <small class="user-name">Shahwez Hassan</small>
            <span class="badge progress-bar-danger" rel="31"></span>
        </span>
        <span style="display: table-cell;vertical-align: middle;" class="user_status">
             
            <span class="user-status is-online"></span>
        </span>
    </div>
    </a>
 </div>
</div>
<!--
| CHAT CONTACT HOVER SECTION
-->
<div class="popover" id="popover-content">
    <div id="contact-image">
                        <img src="assets/images/thumbs/no-image.jpg" class="img-responsive">
           </div>
    <div class="contact-user-info">
        <div id="contact-user-name">Neeraj Neeraj</div>
        <div id="contact-user-status" class="online-status">
             
            <span class="user-status is-offline"></span>
        </div>
    </div>
</div>
<!--
| INDIVIDUAL CHAT SECTION
-->
<div id="chat-box" style="top: 400px">
<div class="chat-box-header">
    <a href="javascript: void(0);" class="chat-box-close pull-right">
        <i class="fa fa-remove"></i>
    </a>
    <span class="user-status is-online"></span>
    <span class="display-name"></span>
    <small></small>
</div>
<div class="chat-container">
    <div class="chat-content">
        <input type="hidden" name="chat_buddy_id" id="chat_buddy_id">
        <ul class="chat-box-body"></ul>
    </div>
    <div class="chat-textarea">
        <input placeholder="Type your message" class="form-control">
    </div>
</div>
</div>
</div>