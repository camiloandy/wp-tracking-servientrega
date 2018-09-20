<?php

if(!function_exists('showOwnAvatar')) {
  function showOwnAvatar( $userId ) 
  {
    if ($userId) {
      $urlPictureUser = wp_get_attachment_url(get_usermeta($userId, 'user_avatar_id'));  
    }  

    if( $urlPictureUser ) {
      $newAvatar = '<img alt="" src="' . $urlPictureUser . '" class="avatar avatar-50 photo avathar-profile" height="50" width="50">';  
      return $newAvatar;  
    } else {
      return get_avatar();
    }   
    
  }  
}
