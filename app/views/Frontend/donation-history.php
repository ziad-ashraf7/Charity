<?php
// Start session to maintain user state
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "charityworks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $connectionError = "Database connection failed: " . $conn->connect_error;
}

// Initialize variables
$donations = [];
$totalDonations = 0;
$yearlyDonations = 0;
$activeRecurring = 0;
$supportedCampaigns = 0;
$errorMsg = '';

// Check if user is logged in (you would have your own auth logic)
$loggedIn = isset($_SESSION['user_id']);
$userId = $loggedIn ? $_SESSION['user_id'] : 0;

// If no user is logged in, you might redirect to login page
// if (!$loggedIn) {
//     header('Location: login.php');
//     exit;
// }

// Get filter parameters
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
$campaign = isset($_GET['campaign']) ? $_GET['campaign'] : '';
$donationType = isset($_GET['donationType']) ? $_GET['donationType'] : '';

// Build query based on filters
$sql = "SELECT d.*, c.title AS campaign_title 
        FROM donations d 
        LEFT JOIN campaigns c ON d.campaign_id = c.id 
        WHERE d.user_id = ?";

$params = array($userId);
$types = "i"; // i for integer (user_id)

if ($startDate) {
    $sql .= " AND d.donation_date >= ?";
    $params[] = $startDate;
    $types .= "s"; // s for string (date)
}

if ($endDate) {
    $sql .= " AND d.donation_date <= ?";
    $params[] = $endDate;
    $types .= "s";
}

if ($campaign) {
    $sql .= " AND d.campaign_id = ?";
    $params[] = $campaign;
    $types .= "i";
}

if ($donationType) {
    $sql .= " AND d.donation_type = ?";
    $params[] = $donationType;
    $types .= "s";
}

$sql .= " ORDER BY d.donation_date DESC";

// Fetch donation history from database if connection is successful
if (!isset($connectionError)) {
    // Get summary statistics
    $statsSql = "SELECT 
                    SUM(amount) as total, 
                    COUNT(DISTINCT campaign_id) as campaigns,
                    SUM(CASE WHEN YEAR(donation_date) = YEAR(CURRENT_DATE()) THEN amount ELSE 0 END) as yearly,
                    COUNT(CASE WHEN is_recurring = 1 AND status = 'active' THEN 1 ELSE NULL END) as recurring
                FROM donations 
                WHERE user_id = ?";
    
    $statsStmt = $conn->prepare($statsSql);
    $statsStmt->bind_param("i", $userId);
    $statsStmt->execute();
    $statsResult = $statsStmt->get_result();
    
    if ($statsRow = $statsResult->fetch_assoc()) {
        $totalDonations = $statsRow['total'] ?: 0;
        $supportedCampaigns = $statsRow['campaigns'] ?: 0;
        $yearlyDonations = $statsRow['yearly'] ?: 0;
        $activeRecurring = $statsRow['recurring'] ?: 0;
    }
    $statsStmt->close();
    
    // Prepare and execute the main query
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        if (count($params) > 0) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
        $stmt->close();
    } else {
        $errorMsg = "Error preparing statement: " . $conn->error;
    }
    
    // Get campaigns for dropdown
    $campaignsSql = "SELECT id, title FROM campaigns ORDER BY title";
    $campaignsResult = $conn->query($campaignsSql);
    $campaigns = [];
    if ($campaignsResult) {
        while ($row = $campaignsResult->fetch_assoc()) {
            $campaigns[] = $row;
        }
    }
}

// For demo purposes, if no database or no entries, show sample data
if (empty($donations) && !$errorMsg) {
    // Sample data for demonstration
    $donations = [
        [
            'id' => 1,
            'donation_date' => '2023-05-15',
            'campaign_title' => 'Clean Water Initiative',
            'amount' => 250.00,
            'donation_type' => 'one-time',
            'status' => 'completed'
        ],
        [
            'id' => 2,
            'donation_date' => '2023-04-22',
            'campaign_title' => 'Food Bank Support',
            'amount' => 100.00,
            'donation_type' => 'monthly',
            'status' => 'active'
        ],
        [
            'id' => 3,
            'donation_date' => '2023-03-10',
            'campaign_title' => 'Education for Children',
            'amount' => 500.00,
            'donation_type' => 'one-time',
            'status' => 'completed'
        ],
        [
            'id' => 4,
            'donation_date' => '2023-02-28',
            'campaign_title' => 'Healthcare for All',
            'amount' => 50.00,
            'donation_type' => 'monthly',
            'status' => 'active'
        ],
        [
            'id' => 5,
            'donation_date' => '2023-01-15',
            'campaign_title' => 'Emergency Relief',
            'amount' => 150.00,
            'donation_type' => 'one-time',
            'status' => 'completed'
        ],
        [
            'id' => 6,
            'donation_date' => '2022-12-24',
            'campaign_title' => 'Food Bank Support',
            'amount' => 200.00,
            'donation_type' => 'one-time',
            'status' => 'completed'
        ],
        [
            'id' => 7,
            'donation_date' => '2022-11-30',
            'campaign_title' => 'Education for Children',
            'amount' => 75.00,
            'donation_type' => 'monthly',
            'status' => 'cancelled'
        ]
    ];
    
    // Set demo values for summary
    if ($totalDonations == 0) {
        $totalDonations = 2450.00;
        $yearlyDonations = 1200.00;
        $activeRecurring = 3;
        $supportedCampaigns = 5;
    }
    
    // Sample campaigns for filter dropdown
    if (empty($campaigns)) {
        $campaigns = [
            ['id' => 1, 'title' => 'Clean Water Initiative'],
            ['id' => 2, 'title' => 'Education for Children'],
            ['id' => 3, 'title' => 'Food Bank Support'],
            ['id' => 4, 'title' => 'Healthcare for All'],
            ['id' => 5, 'title' => 'Emergency Relief']
        ];
    }
}

// Generate PDF function (would be implemented in a real application)
function generatePDF() {
    // This would use a library like FPDF or TCPDF to generate PDF
    // For demo purposes, we're just returning a success message
    return true;
}

// Export to CSV function (would be implemented in a real application)
function exportCSV() {
    // This would generate a CSV file for download
    // For demo purposes, we're just returning a success message
    return true;
}

// Handle PDF or CSV export requests
if (isset($_GET['export'])) {
    $format = $_GET['export'];
    if ($format === 'pdf') {
        $success = generatePDF();
        if ($success) {
            exit('PDF generated and downloaded');
        }
    } elseif ($format === 'csv') {
        $success = exportCSV();
        if ($success) {
            exit('CSV generated and downloaded');
        }
    }
}

// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Donation History | CharityWorks</title>
    <meta name="description" content="Track all your previous donations">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/progressbar_barfiller.css">
    <link rel="stylesheet" href="assets/css/gijgo.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/animated-headline.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- ? Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="assets/img/logo/loder.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
    <header>
        <!-- Header Start -->
        <div class="header-area">
            <div class="main-header ">
                <div class="header-top d-none d-lg-block">
                    <div class="container-fluid">
                        <div class="col-xl-12">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="header-info-left d-flex">
                                    <ul>     
                                        <li>Phone: +99 (0) 101 0000 888</li>
                                        <li>Email: noreply@yourdomain.com</li>
                                    </ul>
                                    <div class="header-social">    
                                        <ul>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                            <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="header-info-right d-flex align-items-center">
                                    <div class="select-this">
                                        <form action="#">
                                            <div class="select-itms">
                                                <select name="select" id="select1">
                                                    <option value="en">English</option>
                                                    <option value="ar">Arabic</option>
                                                    <option value="bn">Bangla</option>
                                                    <option value="hi">Hindi</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <ul class="contact-now">     
                                        <?php if ($loggedIn): ?>
                                            <li><a href="profile.php">My Profile</a></li>
                                            <li><a href="logout.php">Logout</a></li>
                                        <?php else: ?>
                                            <li><a href="../admin/login.view.php">Login</a></li>
                                            <li><a href="register.php">Register</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-bottom header-sticky">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <!-- Logo -->
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="index.php"><img src="assets/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10">
                                <div class="menu-wrapper d-flex align-items-center justify-content-end">
                                    <!-- Main-menu -->
                                    <div class="main-menu d-none d-lg-block">
                                        <nav>
                                            <ul id="navigation">                                                                                          
                                                <li><a href="index.php">Home</a></li>
                                                <li><a href="about.php">About</a></li>
                                                <li><a href="program.php">Latest Causes</a></li>
                                                <li><a href="events.php">Events</a></li>
                                                <li><a href="#">Donation</a>
                                                    <ul class="submenu">
                                                        <li><a href="make-donation.php">Make Donation</a></li>
                                                        <li><a href="donation-history.php">Donation History</a></li>
                                                        <li><a href="zakah-calculator.php">Zakah Calculator</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="blog.php">Blog</a>
                                                    <ul class="submenu">
                                                        <li><a href="blog.php">Blog</a></li>
                                                        <li><a href="blog_details.php">Blog Details</a></li>
                                                        <li><a href="elements.php">Element</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Account</a>
                                                    <ul class="submenu">
                                                        <li><a href="profile.php">My Profile</a></li>
                                                        <li><a href="edit-profile.php">Edit Profile</a></li>
                                                        <li><a href="logout.php">Logout</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="contact.php">Contact</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- Header-btn -->
                                    <div class="header-right-btn d-none d-lg-block ml-20">
                                        <a href="make-donation.php" class="btn header-btn">Donate</a>
                                    </div>
                                </div>
                            </div> 
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->
    </header>
    <main>
        <!-- Hero Start -->
        <div class="slider-area2">
            <div class="slider-height2 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap hero-cap2 pt-70 text-center">
                                <h2>Your Donation History</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->

        <!-- Donation History Section -->
        <section class="contact-section section-padding">
            <div class="container">
                <?php if (isset($connectionError)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $connectionError; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMsg; ?>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <!-- Donation Summary Card -->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="donation-stats">
                                    <div class="stat-item d-flex justify-content-between mb-3">
                                        <span>Total Donations:</span>
                                        <strong>$<?php echo number_format($totalDonations, 2); ?></strong>
                                    </div>
                                    <div class="stat-item d-flex justify-content-between mb-3">
                                        <span>Donations This Year:</span>
                                        <strong>$<?php echo number_format($yearlyDonations, 2); ?></strong>
                                    </div>
                                    <div class="stat-item d-flex justify-content-between mb-3">
                                        <span>Active Recurring:</span>
                                        <strong><?php echo $activeRecurring; ?></strong>
                                    </div>
                                    <div class="stat-item d-flex justify-content-between">
                                        <span>Supported Campaigns:</span>
                                        <strong><?php echo $supportedCampaigns; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Impact Overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="impact-info mb-4">
                                    <h6 class="mb-3">Clean Water Initiative</h6>
                                    <div class="progress mb-2" style="height: 10px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">Your donations helped provide clean water to 125 families</small>
                                </div>
                                
                                <div class="impact-info mb-4">
                                    <h6 class="mb-3">Education for Children</h6>
                                    <div class="progress mb-2" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">Your donations helped 20 children access education</small>
                                </div>
                                
                                <div class="impact-info">
                                    <h6 class="mb-3">Food Bank Support</h6>
                                    <div class="progress mb-2" style="height: 10px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">Your donations helped provide 300 meals to those in need</small>
                                </div>

                                <div class="text-center mt-4">
                                    <a href="#" class="btn btn-outline-primary">View Full Impact Report</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donation History Table -->
                    <div class="col-lg-8">
                        <div class="card shadow mb-5">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Filter Donations</h5>
                            </div>
                            <div class="card-body">
                                <form id="filterForm" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="startDate">From Date</label>
                                                <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo $startDate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="endDate">To Date</label>
                                                <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo $endDate; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="campaign">Campaign</label>
                                                <select class="form-control" id="campaign" name="campaign">
                                                    <option value="">All Campaigns</option>
                                                    <?php foreach ($campaigns as $c): ?>
                                                    <option value="<?php echo $c['id']; ?>" <?php echo $campaign == $c['id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($c['title']); ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="donationType">Donation Type</label>
                                                <select class="form-control" id="donationType" name="donationType">
                                                    <option value="">All Types</option>
                                                    <option value="one-time" <?php echo $donationType == 'one-time' ? 'selected' : ''; ?>>One-time</option>
                                                    <option value="monthly" <?php echo $donationType == 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                                                    <option value="quarterly" <?php echo $donationType == 'quarterly' ? 'selected' : ''; ?>>Quarterly</option>
                                                    <option value="annually" <?php echo $donationType == 'annually' ? 'selected' : ''; ?>>Annual</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-outline-secondary">Reset</a>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Donation History</h5>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?export=pdf&<?php echo $_SERVER['QUERY_STRING']; ?>" class="btn btn-outline-primary btn-sm" id="downloadPdf">
                                        <i class="fas fa-file-pdf mr-2"></i>PDF
                                    </a>
                                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?export=csv&<?php echo $_SERVER['QUERY_STRING']; ?>" class="btn btn-outline-primary btn-sm" id="downloadCsv">
                                        <i class="fas fa-file-csv mr-2"></i>CSV
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($donations)): ?>
                                <div class="alert alert-info">
                                    No donations found matching your criteria.
                                </div>
                                <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Campaign</th>
                                                <th>Amount</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donations as $index => $donation): ?>
                                            <tr>
                                                <td><?php echo date('Y-m-d', strtotime($donation['donation_date'])); ?></td>
                                                <td><?php echo htmlspecialchars($donation['campaign_title']); ?></td>
                                                <td>$<?php echo number_format($donation['amount'], 2); ?></td>
                                                <td><?php echo ucfirst($donation['donation_type']); ?></td>
                                                <td>
                                                    <?php if ($donation['status'] == 'completed'): ?>
                                                    <span class="badge badge-success">Completed</span>
                                                    <?php elseif ($donation['status'] == 'active'): ?>
                                                    <span class="badge badge-success">Active</span>
                                                    <?php elseif ($donation['status'] == 'cancelled'): ?>
                                                    <span class="badge badge-danger">Cancelled</span>
                                                    <?php else: ?>
                                                    <span class="badge badge-secondary"><?php echo ucfirst($donation['status']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $index+1; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $index+1; ?>">
                                                            <a class="dropdown-item" href="view-receipt.php?id=<?php echo $donation['id']; ?>"><i class="fas fa-receipt mr-2"></i>View Receipt</a>
                                                            
                                                            <?php if ($donation['status'] == 'completed'): ?>
                                                            <a class="dropdown-item" href="make-donation.php?campaign=<?php echo $donation['id']; ?>"><i class="fas fa-heart mr-2"></i>Donate Again</a>
                                                            <a class="dropdown-item" href="share-impact.php?id=<?php echo $donation['id']; ?>"><i class="fas fa-share-alt mr-2"></i>Share Impact</a>
                                                            
                                                            <?php elseif ($donation['status'] == 'active'): ?>
                                                            <a class="dropdown-item" href="edit-recurring.php?id=<?php echo $donation['id']; ?>"><i class="fas fa-edit mr-2"></i>Edit Recurring</a>
                                                            <a class="dropdown-item" href="cancel-donation.php?id=<?php echo $donation['id']; ?>" onclick="return confirm('Are you sure you want to cancel this recurring donation?');"><i class="fas fa-times-circle mr-2"></i>Cancel</a>
                                                            
                                                            <?php elseif ($donation['status'] == 'cancelled'): ?>
                                                            <a class="dropdown-item" href="renew-donation.php?id=<?php echo $donation['id']; ?>"><i class="fas fa-heart mr-2"></i>Renew Donation</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination - you would implement this dynamically -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-4">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Donation History Section End -->

        <!-- Year-End Summary Section -->
        <section class="section-padding bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h3>Your <?php echo date('Y'); ?> Donation Summary</h3>
                            <p class="text-muted">Thank you for your generosity throughout the year</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="display-4 text-primary mb-3">$<?php echo number_format($yearlyDonations, 0); ?></div>
                                <h5>Total Donated</h5>
                                <p class="text-muted">in <?php echo date('Y'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="display-4 text-success mb-3"><?php echo $supportedCampaigns; ?></div>
                                <h5>Campaigns Supported</h5>
                                <p class="text-muted">across different causes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="display-4 text-info mb-3"><?php echo $activeRecurring; ?></div>
                                <h5>Active Recurring</h5>
                                <p class="text-muted">monthly donations</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <a href="tax-receipt.php?year=<?php echo date('Y'); ?>" class="btn btn-primary">View Tax Receipt</a>
                        <a href="share-impact.php" class="btn btn-outline-primary ml-2">Share Your Impact</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Year-End Summary Section End -->
    </main>
    <footer>
        <div class="footer-wrapper section-bg2" data-background="assets/img/gallery/footer_bg.png">
            <!-- Footer Top-->
            <div class="footer-area footer-padding">
                <div class="container">
                    <div class="row d-flex justify-content-between">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <div class="footer-tittle">
                                    <div class="footer-logo mb-20">
                                        <a href="index.php"><img src="assets/img/logo/logo2_footer.png" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Contact Info</h4>
                                    <ul>
                                        <li>
                                            <p>Address: Your address goes here, your demo address.</p>
                                        </li>
                                        <li><a href="#">Phone: +8880 44338899</a></li>
                                        <li><a href="#">Email: info@colorlib.com</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Important Link</h4>
                                    <ul>
                                        <li><a href="#"> View Project</a></li>
                                        <li><a href="#">Contact Us</a></li>
                                        <li><a href="#">Testimonial</a></li>
                                        <li><a href="#">Support</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Newsletter</h4>
                                    <div class="footer-pera footer-pera2">
                                    <p>Heaven fruitful doesn't over lesser in days. Appear creeping.</p>
                                </div>
                                <!-- Form -->
                                <div class="footer-form">
                                    <div id="mc_embed_signup">
                                        <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe_form relative mail_part">
                                            <input type="email" name="email" id="newsletter-form-email" placeholder="Email Address" class="placeholder hide-on-focus" onfocus="this.placeholder = ''" onblur="this.placeholder = ' Email Address '">
                                            <div class="form-icon">
                                                <button type="submit" name="submit" id="newsletter-submit" class="email_icon newsletter-submit button-contactForm"><img src="assets/img/gallery/form.png" alt=""></button>
                                            </div>
                                            <div class="mt-10 info"></div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer-bottom -->
            <div class="footer-bottom-area">
                <div class="container">
                    <div class="footer-border">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="col-xl-10 col-lg-9">
                                <div class="footer-copy-right">
                                    <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3">
                                <div class="footer-social f-right">
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fas fa-globe"></i></a>
                                    <a href="#"><i class="fab fa-behance"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Up -->
    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.slicknav.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>
    <script src="./assets/js/gijgo.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>
    <script src="./assets/js/jquery.barfiller.js"></script>
    <script src="./assets/js/jquery.counterup.min.js"></script>
    <script src="./assets/js/waypoints.min.js"></script>
    <script src="./assets/js/jquery.countdown.min.js"></script>
    <script src="./assets/js/hover-direction-snake.min.js"></script>
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize nice select for dropdowns
            $('select').niceSelect();
            
            // Initialize datepicker for date fields
            $('#startDate, #endDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            
            // AJAX export functionality can be added here
            // For now we're using traditional form submission
        });
    </script>
</body>
</html>