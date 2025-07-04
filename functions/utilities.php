<?php

/**
 * Get user information by ID.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return array|null
 */
function getUserInfo($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$user_id]);
        return $query->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user info: " . $e->getMessage());
        return null;
    }
}

/**
 * Get wallet balance for a user.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return float
 */
function getWalletBalance($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT balance FROM wallets WHERE user_id = ?");
        $query->execute([$user_id]);
        $result = $query->fetch();
        return $result ? (float)$result['balance'] : 0.00;
    } catch (PDOException $e) {
        error_log("Error fetching wallet balance: " . $e->getMessage());
        return 0.00;
    }
}

/**
 * Get total bookings for a customer.
 *
 * @param PDO $pdo
 * @param int $customer_id
 * @return int
 */
function getTotalBookings($pdo, $customer_id)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE customer_id = ?");
        $query->execute([$customer_id]);
        $result = $query->fetch();
        return $result ? (int)$result['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error fetching total bookings: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get total jobs completed by a provider.
 *
 * @param PDO $pdo
 * @param int $provider_id
 * @return int
 */
function getTotalJobsCompleted($pdo, $provider_id)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE provider_id = ? AND status = 'completed'");
        $query->execute([$provider_id]);
        $result = $query->fetch();
        return $result ? (int)$result['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error fetching total jobs completed: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get average rating for a provider.
 *
 * @param PDO $pdo
 * @param int $provider_id
 * @return float
 */
function getAverageRating($pdo, $provider_id)
{
    try {
        $query = $pdo->prepare("SELECT AVG(stars) AS average FROM ratings WHERE provider_id = ?");
        $query->execute([$provider_id]);
        $result = $query->fetch();
        return $result ? round((float)$result['average'], 2) : 0.00;
    } catch (PDOException $e) {
        error_log("Error fetching average rating: " . $e->getMessage());
        return 0.00;
    }
}

/**
 * Get total revenue for a provider.
 *
 * @param PDO $pdo
 * @param int $provider_id
 * @return float
 */
function getTotalRevenue($pdo, $provider_id)
{
    try {
        $query = $pdo->prepare("SELECT SUM(amount) AS total FROM transactions WHERE user_id = ? AND type = 'release'");
        $query->execute([$provider_id]);
        $result = $query->fetch();
        return $result ? (float)$result['total'] : 0.00;
    } catch (PDOException $e) {
        error_log("Error fetching total revenue: " . $e->getMessage());
        return 0.00;
    }
}

/**
 * Check if a user is an admin.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return bool
 */
function isAdmin($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $query->execute([$user_id]);
        $result = $query->fetch();
        return $result && $result['role'] === 'admin';
    } catch (PDOException $e) {
        error_log("Error checking admin status: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if a user exists by email.
 *
 * @param PDO $pdo
 * @param string $email
 * @return bool
 */
function userExists($pdo, $email)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM users WHERE email = ?");
        $query->execute([$email]);
        $result = $query->fetch();
        return $result && $result['total'] > 0;
    } catch (PDOException $e) {
        error_log("Error checking user existence: " . $e->getMessage());
        return false;
    }
}

/**
 * Get service providers by service ID.
 *
 * @param PDO $pdo
 * @param int $service_id
 * @return array
 */
function getServiceProvidersByService($pdo, $service_id)
{
    try {
        $query = $pdo->prepare("SELECT * FROM users WHERE role = 'provider' AND service_id = ?");
        $query->execute([$service_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching service providers: " . $e->getMessage());
        return [];
    }
}

/**
 * Get service details by ID.
 *
 * @param PDO $pdo
 * @param int $service_id
 * @return array|null
 */
function getServiceDetails($pdo, $service_id)
{
    try {
        $query = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $query->execute([$service_id]);
        return $query->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching service details: " . $e->getMessage());
        return null;
    }
}

function getUserBookings($pdo, $customer_id)
{
    try {
        // Fetch bookings for the user
        $query = $pdo->prepare("SELECT * FROM bookings WHERE customer_id = ? ORDER BY scheduled_date DESC");
        $query->execute([$customer_id]);
        $bookings = $query->fetchAll(PDO::FETCH_ASSOC);

        // Fetch additional details for each booking
        foreach ($bookings as &$booking) {
            // Fetch provider details
            $providerQuery = $pdo->prepare("SELECT title FROM providers WHERE id = ?");
            $providerQuery->execute([$booking['provider_id']]);
            $provider = $providerQuery->fetch();
            $booking['provider_title'] = $provider['title'] ?? 'Unknown Provider';

            // Fetch service details
            $serviceQuery = $pdo->prepare("SELECT title FROM services WHERE id = ?");
            $serviceQuery->execute([$booking['service_id']]);
            $service = $serviceQuery->fetch();
            $booking['service_title'] = $service['title'] ?? 'Unknown Service';
        }

        return $bookings;
    } catch (PDOException $e) {
        error_log("Error fetching bookings: " . $e->getMessage());
        return [];
    }
}
