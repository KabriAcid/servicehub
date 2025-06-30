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

?>