<div class="topnav">

    <a href="Feed.php" class="active">DS Estate</a> <!-- company name -->

    <!-- dropdown menu links container -->
    <div id="myLinks">

        <!-- link to Feed page -->
        <a href="Feed.php">Feed</a>

        <!-- display different links based on user session -->
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>

            <!-- display these links if user is logged in -->
            <a href="CreateListing.php">Create Listing</a>
            <a href="Logout.php" id="tmp">Logout</a>

        <?php else: ?>

            <!-- display these links if user is not logged in -->
            <a href="Login_Register.php?redirect=CreateListing" id="tmp">Create Listing</a>
            <a href="Login_Register.php" id="tmp">Login</a>

        <?php endif; ?>

    </div>

    <!-- hamburger icon for mobile menu toggle -->
    <a href="javascript:void(0);" class="icon" onclick="toggleMenu()">
        <i class="fa fa-bars"></i> 
    </a>

</div>

<!-- external JavaScript file for menu functionality -->
<script src="navbar.js"></script>