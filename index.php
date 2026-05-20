<?php include("includes/header.php"); ?>

<!-- Hero Section -->
<section class="hero-section position-relative bg-dark text-white text-center py-5" style="background: url('assets/images/house1.jpg') center/cover no-repeat;">
    <div class="container">
        <h1 class="display-4 fw-bold">Find Your Ideal Student Rental Apartment</h1>
        <p class="lead mb-4">Browse, Book, and Move In – All in One Place!</p>

        <!-- Search Bar -->
        <form class="d-flex justify-content-center">
            <div class="input-group w-75 w-md-50 shadow-lg">
                <input type="text" class="form-control" placeholder="Search by location, type or price...">
                <button class="btn btn-primary px-4" type="submit">Search</button>
            </div>
        </form>
    </div>
</section>

<!-- Quick Filters -->
<section class="container py-5">
    <h3 class="text-center mb-4">Quick Filters</h3>
    <div class="row justify-content-center g-3">
        <div class="col-md-3">
            <select class="form-select">
                <option selected>Price Range</option>
                <option value="1">Below 5,000 KES</option>
                <option value="2">5,000 - 10,000 KES</option>
                <option value="3">Above 10,000 KES</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected>Location</option>
                <option value="1">Kesses</option>
                <option value="2">Annex</option>
                <option value="3">Kapsoya</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected>Property Type</option>
                <option value="1">Apartment</option>
                <option value="2">Bedsitter</option>
                <option value="3">Bungalow</option>
            </select>
        </div>
    </div>
</section>

<!-- Featured Listings Carousel -->
<section class="bg-light py-5">
    <div class="container">
        <h3 class="text-center mb-4">Featured Listings</h3>
        <div id="listingCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Item 1 -->
                <div class="carousel-item active">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card shadow">
                                <img src="assets/images/house1.jpg" class="card-img-top" alt="House 1">
                                <div class="card-body">
                                    <h5 class="card-title">Modern Bedsitter</h5>
                                    <p class="card-text">Located in Kesses • KES 6,000/month</p>
                                    <a href="pages/available_rentals.php" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow">
                                <img src="assets/images/house2.jpg" class="card-img-top" alt="House 2">
                                <div class="card-body">
                                    <h5 class="card-title">Flying Eagle Apartments</h5>
                                    <p class="card-text">Located in Chebarus • KES 10,000/month</p>
                                    <a href="pages/available_rentals.php" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow">
                                <img src="assets/images/house3.jpg" class="card-img-top" alt="House 3">
                                <div class="card-body">
                                    <h5 class="card-title">VillaCity Courts</h5>
                                    <p class="card-text">Located in Talai Centre • KES 5,000/month</p>
                                    <a href="pages/available_rentals.php" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow">
                                <img src="assets/images/house4.jpg" class="card-img-top" alt="House 4">
                                <div class="card-body">
                                    <h5 class="card-title">LUXEMBURG APARTMENTS</h5>
                                    <p class="card-text">Located in Talai Centre • KES 7,000/month</p>
                                    <a href="pages/available_rentals.php" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow">
                                <img src="assets/images/house5.jpg" class="card-img-top" alt="House 5">
                                <div class="card-body">
                                    <h5 class="card-title">Tuff Greens Hostels</h5>
                                    <p class="card-text">Located in Kesses • KES 12,000/month</p>
                                    <a href="pages/available_rentals.php" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <!-- More cards can be added here -->
                    </div>
                </div>
                <!-- Add more carousel-item divs for additional listings -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 text-center bg-primary text-white">
    <div class="container">
        <h2>Are you a student or a landlord?</h2>
        <p>Get started today by creating an account.</p>
        <a href="auth/register.php" class="btn btn-light m-2">I'm a Student</a>
        <a href="auth/register.php?role=landlord" class="btn btn-outline-light m-2">I'm a Landlord</a>
    </div>
</section>

<?php include("includes/footer.php"); ?>
