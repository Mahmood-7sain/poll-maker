<header>
    <div class="logo">
        <h1 class="poll">Poll</h1>
        <h2 class="maker">Maker</h2>
    </div>
    <span class="burger-icon" onclick="toggleNav()">☰</span>
    <nav class="nav1">
            <a href='index.php' class="myButton1">Home</a>
            <a href="create-poll.php" class="myButton1">Create Poll</a>

        <?php
                if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']==true){
                    echo '<a href="logout.php" class="myButton">Logout</a>';
                }else{
                    echo '<a href="signup.php" class="myButton">Sign Up</a>';
                    echo '<a href="login.php" class="myButton">Login</a>';
                }
                ?>
    </nav>
</header>
<script>
function toggleNav() {
    var nav = document.querySelector('.nav1');
    nav.classList.toggle('active');

    // Get all anchor tags inside the nav with class "nav1"
    var anchorTags = document.querySelectorAll('.nav1 a');
    // Loop through each anchor tag and set the border-bottom style
    for (var i = 0; i < anchorTags.length-1; i++) {
        anchorTags[i].style.borderBottom = "1px solid white";
    }
}
</script>