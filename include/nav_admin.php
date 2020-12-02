<?php
//======================================================================
// ADMINISTRATOR MENU
//======================================================================
?>
<li class="nav-item active">
  <a class="nav-link" href="<?php echo BASE_URL; ?>/admin/">Home <span class="sr-only">(current)</span></a>
</li>
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Music
  </a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/music_add.php">Add Music</a>
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/music_search.php">Search Music</a>
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/music_artist.php">All Artist</a>
  </div>
</li>
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Playlists
  </a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/playlist_search.php">Search Playlist</a>
    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/playlist.php">All Playlist</a>
    
  </div>
</li>
<li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/admin/user.php">Users</a></li>
<li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/db/logout.php">Logout</a></li>