<?php
//======================================================================
// GENERAL USER MENU
//======================================================================
?>
<li class="nav-item active">
  <a class="nav-link" href="<?php echo BASE_URL; ?>/user/">Home <span class="sr-only">(current)</span></a>
</li>
<li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/user/search_music.php">Search Music</a></li>
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Playlists
  </a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/user/browse_playlists.php">Browse Playlists</a>
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/user/create_playlist.php">Create Playlist</a>
  </div>
</li>
<li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/user/user_account.php">Account</a></li>
<li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/db/logout.php">logout</a></li>