<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Dashboard - Turn Key Pro Services</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" 
        integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/responsive.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" 
        integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="icon" type="image/png" href="images/favicon.ico">
  <style>
    :root {
      --primary-yellow: #FFC107;
      --primary-yellow-dark: #FFA000;
      --dark-text: #333;
      --white-text: #FFF;
    }

    /* Header Styles */
    .header-container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 0;
    }

    .logo-container {
      width: 244px !important;
      height: 244px !important;
      margin: 0 auto !important;
      border-radius: 50% !important;
      overflow: hidden !important;
    }

    .company-logo {
      width: 244px !important;
      height: 244px !important;
      max-width: 244px !important;
      display: block !important;
      border-radius: 50% !important;
      object-fit: cover !important;
      border: 3px solid var(--primary-yellow-dark) !important;
    }

    .nav-container {
      width: 100% !important;
      background-color: var(--primary-yellow-dark) !important;
      padding: 15px 0 !important;
    }

    .nav-inner {
      width: 100% !important;
      max-width: 1200px !important;
      margin: 0 auto !important;
      padding: 0 20px !important;
    }

    .nav-menu {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      width: 100% !important;
      margin: 0 !important;
      padding: 0 !important;
      list-style: none !important;
    }

    .nav-menu li {
      flex: 1 !important;
      text-align: center !important;
    }

    .nav-menu li a {
      color: white !important;
      text-decoration: none !important;
      font-size: 16px !important;
      padding: 10px 15px !important;
      display: block !important;
      transition: all 0.3s ease !important;
    }

    .nav-menu li a:hover {
      color: var(--dark-text) !important;
      background-color: rgba(255, 255, 255, 0.1) !important;
    }

    .nav-menu li a.active {
      color: var(--dark-text) !important;
      background-color: rgba(255, 255, 255, 0.2) !important;
    }

    /* Dropdown Menu Styles */
    .dropdown-menu {
      background-color: var(--primary-yellow) !important;
      border: none !important;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
      padding: 10px 0 !important;
      min-width: 200px !important;
    }

    .dropdown-item {
      color: var(--white-text) !important;
      padding: 8px 20px !important;
      font-size: 14px !important;
      transition: all 0.3s ease !important;
    }

    .dropdown-item:hover {
      background-color: var(--primary-yellow-dark) !important;
      color: var(--dark-text) !important;
    }

    .dropdown-item i {
      margin-right: 10px !important;
      width: 20px !important;
      text-align: center !important;
    }

    .dropdown-divider {
      border-top: 1px solid rgba(255,255,255,0.2) !important;
      margin: 8px 0 !important;
    }

    .dropdown-toggle::after {
      margin-left: 8px !important;
    }

    .nav-link.dropdown-toggle {
      color: var(--white-text) !important;
    }

    .nav-link.dropdown-toggle:hover {
      color: var(--dark-text) !important;
    }

    @media (max-width: 768px) {
      .nav-menu {
        flex-direction: column !important;
        gap: 10px !important;
      }
      
      .nav-menu li {
        width: 100% !important;
      }
    }

    /* Dashboard Styles */
    .dashboard-container {
      max-width: 1200px;
      margin: 50px auto;
      padding: 20px;
    }

    .welcome-section {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .dashboard-card {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .dashboard-card h3 {
      color: var(--dark-text);
      margin-bottom: 15px;
    }

    .dashboard-card p {
      color: #666;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .user-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--primary-yellow);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: var(--dark-text);
    }

    .user-details h2 {
      margin: 0;
      color: var(--dark-text);
    }

    .user-details p {
      margin: 5px 0;
      color: #666;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="main-header">
    <div class="header-container">
      <div class="logo-container">
        <img src="images/FB_IMG_1741285493669.jpg" 
             alt="Turn-Key Pro Services Logo" 
             class="company-logo">
      </div>
    </div>
    
    <div class="nav-container">
      <div class="nav-inner">
        <ul class="nav-menu">
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="why-choose-us.html">Why Choose Us</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user"></i> Account
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
              <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Dashboard Content -->
  <section class="dashboard-section">
    <div class="dashboard-container">
      <div class="welcome-section">
        <div class="user-info">
          <div class="user-avatar">
            <i class="fas fa-user"></i>
          </div>
          <div class="user-details">
            <h2>Welcome, <?php echo htmlspecialchars($email); ?></h2>
            <p>Role: <?php echo htmlspecialchars($role); ?></p>
          </div>
        </div>
      </div>

      <div class="dashboard-grid">
        <div class="dashboard-card">
          <h3><i class="fas fa-calendar-check"></i> Your Subscriptions</h3>
          <p>View and manage your current service subscriptions.</p>
          <a href="#" class="btn btn-primary">View Subscriptions</a>
        </div>

        <div class="dashboard-card">
          <h3><i class="fas fa-tools"></i> Service Requests</h3>
          <p>Track your current service requests and maintenance schedules.</p>
          <a href="#" class="btn btn-primary">View Requests</a>
        </div>

        <div class="dashboard-card">
          <h3><i class="fas fa-file-invoice"></i> Billing</h3>
          <p>View your billing history and manage payment methods.</p>
          <a href="#" class="btn btn-primary">View Billing</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Copyright Section -->
  <div class="copyright-section">
    <div class="container">
      <div class="text-center py-3">
        2024 © Turn Key Pro Services. Website Designed by Media department
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="js/wow.min.js"></script>
  <script src="js/scrollIt.min.js"></script>
  <script>
    new WOW().init();
    $(function() {
      $.scrollIt();
    });
    $(document).ready(function() {
      $('.dropdown-toggle').dropdown();
      $(document).click(function(e) {
        if (!$(e.target).closest('.dropdown').length) {
          $('.dropdown-menu').removeClass('show');
        }
      });
    });
  </script>
</body>
</html> 