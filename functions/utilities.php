<?php
function getUserInfo($pdo, $user_id)
{
    try {
        // First check in 'users' table
        $stmt = $pdo->prepare("SELECT *, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user) {
            $user['role'] = $user['role'] ?? 'user'; // Just in case role is missing
            return $user;
        }

        // If not found, check in 'admin' table
        $stmt = $pdo->prepare("SELECT *, 'admin' AS role FROM admin WHERE id = ?");
        $stmt->execute([$user_id]);
        $admin = $stmt->fetch();

        if ($admin) {
            return $admin;
        }
    } catch (PDOException $e) {
        error_log("Error fetching user info: " . $e->getMessage());
    }

    return null; // Not found
}

/**
 * Retrieve service provider details from providers table by user_id.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return array|null
 */
function getProviderDetails($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $provider = $stmt->fetch();

        return $provider ? $provider : null;
    } catch (PDOException $e) {
        error_log("Error fetching provider details: " . $e->getMessage());
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
 * @param int $user_id
 * @return int
 */
function getTotalBookings($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
        $query->execute([$user_id]);
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
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE service_id = ? AND status = 'completed'");
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

function getUserBookings($pdo, $user_id)
{
    try {
        // Fetch bookings for the user
        $query = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY scheduled_date DESC");
        $query->execute([$user_id]);
        $bookings = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($bookings as &$booking) {
            // Since service represents both service and provider
            $serviceQuery = $pdo->prepare("SELECT * FROM services WHERE id = ?");
            $serviceQuery->execute([$booking['service_id']]);
            $service = $serviceQuery->fetch();

            $booking['service_title'] = $service['title'] ?? 'Unknown Service';
            $booking['provider_title'] = $service['title'] ?? 'Unknown Provider';  // Same as service title
            $booking['amount'] = $service['price'] ?? 0;
        }

        return $bookings;
    } catch (PDOException $e) {
        error_log("Error fetching bookings: " . $e->getMessage());
        return [];
    }
}

function getServiceWithProvider(PDO $pdo, $service_id)
{
    try {
        // Fetch the service
        $serviceStmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $serviceStmt->execute([$service_id]);
        $service = $serviceStmt->fetch(PDO::FETCH_ASSOC);

        if (!$service) {
            error_log("Service with ID $service_id not found.");
            return null; // Service not found
        }

        // Fetch the provider (user)
        $providerStmt = $pdo->prepare("SELECT id, full_name, email, phone, address, city FROM users WHERE id = ? AND is_provider = 1");
        $providerStmt->execute([$service['user_id']]);
        $provider = $providerStmt->fetch(PDO::FETCH_ASSOC);

        if (!$provider) {
            error_log("Provider for service ID $service_id not found.");
            return null; // Provider not found
        }

        // Combine service and provider data
        return [
            'service' => $service,
            'provider' => $provider
        ];
    } catch (PDOException $e) {
        error_log("Error fetching service with provider: " . $e->getMessage());
        return null;
    }
}
