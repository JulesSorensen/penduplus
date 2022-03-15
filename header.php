<nav class="bg-gray-800">
  <div class="mx-auto px-2 sm:px-6 lg:px-8">
    <div class="relative flex items-center justify-between h-16">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">

        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="sr-only">Open main menu</span>

          <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>

          <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex-shrink-0 flex items-center">
          <img class="block lg:left h-8 w-auto" src="images/photoplus.png" alt="Workflow">
        </div>
        <div class="hidden sm:block sm:ml-6">
          <div class="flex space-x-4">
            <a href="index.php?p=home" class="<?php if ($_GET["p"] == "home") {echo "bg-gray-900 text-white";} else {echo "text-gray-300";} ?> px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Home</a>

            <a href="index.php?p=sub" class="<?php if ($_GET["p"] == "sub") {echo "bg-gray-900 text-white";} else {echo "text-gray-300";} ?>  hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Subscription</a>
          </div>
        </div>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <div class="flex flex-row mr-3">
          <?php 
            if (!isset($_SESSION['sub']) || $_SESSION['sub'] == 'free') {
              ?>
              <div class="bg-gray-200 rounded-lg px-2 py-1 cursor-pointer hover:bg-gray-300 transition-all duration-500">
                <p class="select-none text-black font-semibold">FREE</p>
              </div>
              <?php
            } else if ($_SESSION['sub'] == 'plusSub') {
              ?>
              <div class="bg-green-200 rounded-lg px-2 py-1 cursor-pointer hover:bg-green-300 transition-all duration-500">
                <p class="select-none text-black font-semibold">PLUS</p>
              </div>
              <?php
            } else if ($_SESSION['sub'] == 'goldSub') {
              ?>
              <div class="bg-yellow-300 rounded-lg px-2 py-1 cursor-pointer hover:bg-yellow-200 transition-all duration-500">
                <p class="select-none text-black font-semibold">GOLD</p>
              </div>
              <?php
            }
          ?>
        </div>
        <div class="ml-3 relative">
          <div>
            <button type="button" onClick='toggleMenu()' class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
              <span class="sr-only">Open user menu</span>
              <img class="h-9 w-9 rounded-full"
              <?php
                if (!isset($_SESSION['img']) || $_SESSION['img'] == 'default') echo 'src="images/user.png"';
              ?>
              alt="">
            </button>
          </div>

          <div id='menu' class="invisible z-10 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
            <a href="index.php?p=profile" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Profile</a>
            <?php if (isset($_SESSION['admin'])) { echo '<a href="index.php?p=admin" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Admin</a>'; } ?>
            <a href="index.php?p=login" class="block px-4 py-2 text-sm text-red-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="sm:hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1">
      <a href="index.php=home" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Home</a>

      <a href="index.php=sub" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Subscription</a>
    </div>
  </div>
</nav>

<script>
  e = true;
  function toggleMenu() {
      if(e == true) {
          document.getElementById("menu").style.visibility = 'visible';
          e = false;
      }
      else if (e == false) {
          document.getElementById("menu").style.visibility = 'hidden';
          e = true;
      }
  }
</script>