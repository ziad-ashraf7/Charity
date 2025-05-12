<?php loadPartial(\Core\System::HEADER); ?>
<body>
<!-- ? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="../backend/public/assets/img/logo/loder.png" alt="">
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->
<?php loadPartial(\Core\System::NAVBAR); ?>

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
                                    <strong><?php echo $totalDonations ?></strong>
                                </div>
                                <div class="stat-item d-flex justify-content-between mb-3">
                                    <span>Donations This Year:</span>
                                    <strong><?php echo $totalDonationNumber ?> L.E</strong>
                                </div>
                                <div class="stat-item d-flex justify-content-between mb-3">
                                    <span>Active Recurring:</span>
                                    <strong><?php echo $activeRecurring ?></strong>
                                </div>
                                <div class="stat-item d-flex justify-content-between">
                                    <span>Supported Campaigns:</span>
                                    <strong><?php echo $supportedCampaigns ?></strong>
                                </div>
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
                            <form id="filterForm">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="startDate">From Date</label>
                                            <input type="date" class="form-control" name="fromDate" id="startDate">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="endDate">To Date</label>
                                            <input type="date" class="form-control" name="toDate" id="endDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="campaign">Campaign</label>
                                            <select class="form-control" name="campaignId" id="campaign">
                                                <?php foreach ($campaigns as $campaign): ?>
                                                    <option value="<?php echo $campaign->id ?>"><?php echo $campaign->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="donationType">Donation Type</label>
                                            <select class="form-control" name="donationType" id="donationType">
                                                <?php foreach ($donationTypes as $donationType): ?>
                                                    <option value="<?php echo $donationType->value ?>"><?php echo $donationType->value ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Donation History</h5>
                        </div>
                        <div class="card-body">
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
                                    <?php foreach ($donations as $donation): ?>
                                        <tr>
                                            <td><?php echo $donation->created_at ?></td>
                                            <td><?php echo $donation->campaign_id ?></td>
                                            <td><?php echo $donation->amount ?> L.E</td>
                                            <td><?php echo $donation->donation_type ?></td>
                                            <td>
                                                <?php if ($donation->donation_type == \App\Enum\DonationTypeEnum::ONE_TIME->value): ?>
                                                    <span class="badge badge-success">Completed</span>
                                                <?php elseif ($donation->donation_type != \App\Enum\DonationTypeEnum::ONE_TIME->value && $donation->is_active): ?>
                                                    <span class="badge badge-info">Active</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">In-Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton1"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <a class="dropdown-item" href="#"><i
                                                                    class="fas fa-receipt mr-2"></i>View Details</a>
                                                        <?php if ($donation->donation_type != \App\Enum\DonationTypeEnum::ONE_TIME->value && $donation->is_active): ?>
                                                            <a class="dropdown-item" href="#">Make Donation Inactive</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
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
                        <h3>Your 2023 Donation Summary</h3>
                        <p class="text-muted">Thank you for your generosity throughout the year</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="display-4 text-primary mb-3">$1,200</div>
                            <h5>Total Donated</h5>
                            <p class="text-muted">in 2023</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="display-4 text-success mb-3">5</div>
                            <h5>Campaigns Supported</h5>
                            <p class="text-muted">across different causes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="display-4 text-info mb-3">3</div>
                            <h5>Active Recurring</h5>
                            <p class="text-muted">monthly donations</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <a href="#" class="btn btn-primary">View Tax Receipt</a>
                    <a href="#" class="btn btn-outline-primary ml-2">Share Your Impact</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Year-End Summary Section End -->
</main>
<?php loadPartial(\Core\System::FOOTER); ?>

<!-- Scroll Up -->
<div id="back-top">
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<!-- JS here -->
<?php loadPartial(\Core\System::JS); ?>
</body>
</html>