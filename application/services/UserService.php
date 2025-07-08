<?php

/**
 * User Service Layer.
 *
 * Kullanıcı işlemlerinin iş mantığını yönetir
 * Controller'lardan ayrıştırılmış business logic
 */
class UserService
{
    private $CI;

    private $user_model;

    private $user_role_model;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('users/user_model');
        $this->CI->load->model('user_roles/user_role_model');
        $this->user_model = $this->CI->user_model;
        $this->user_role_model = $this->CI->user_role_model;
    }

    /**
     * Kullanıcı oluşturma iş mantığı.
     */
    public function createUser($userData)
    {
        try {
            // Validation
            if (empty($userData['username']) || empty($userData['email'])) {
                throw new InvalidArgumentException('Username and email are required');
            }

            // Email unique kontrolü
            if ($this->isEmailExists($userData['email'])) {
                throw new InvalidArgumentException('Email already exists');
            }

            // Username unique kontrolü
            if ($this->isUsernameExists($userData['username'])) {
                throw new InvalidArgumentException('Username already exists');
            }

            // Password hash
            if (!empty($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            }

            // Default values
            $userData['isActive'] = $userData['isActive'] ?? 1;
            $userData['createdAt'] = date('Y-m-d H:i:s');

            // Database transaction
            $this->CI->db->trans_start();

            $userId = $this->user_model->add($userData);

            if (!$userId) {
                throw new RuntimeException('Failed to create user');
            }

            $this->CI->db->trans_complete();

            if ($this->CI->db->trans_status() === false) {
                throw new RuntimeException('Transaction failed');
            }

            return [
                'success' => true,
                'user_id' => $userId,
                'message' => 'User created successfully',
            ];
        } catch (Exception $e) {
            $this->CI->db->trans_rollback();

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kullanıcı güncelleme iş mantığı.
     */
    public function updateUser($userId, $userData)
    {
        try {
            // Kullanıcı var mı kontrol et
            $existingUser = $this->user_model->get(['id' => $userId]);
            if (!$existingUser) {
                throw new InvalidArgumentException('User not found');
            }

            // Email unique kontrolü (kendisi hariç)
            if (!empty($userData['email']) && $userData['email'] !== $existingUser->email) {
                if ($this->isEmailExists($userData['email'])) {
                    throw new InvalidArgumentException('Email already exists');
                }
            }

            // Username unique kontrolü (kendisi hariç)
            if (!empty($userData['username']) && $userData['username'] !== $existingUser->username) {
                if ($this->isUsernameExists($userData['username'])) {
                    throw new InvalidArgumentException('Username already exists');
                }
            }

            // Password hash (eğer yeni password varsa)
            if (!empty($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            } else {
                unset($userData['password']); // Boş password'u kaldır
            }

            $userData['updatedAt'] = date('Y-m-d H:i:s');

            // Update
            $result = $this->user_model->update(['id' => $userId], $userData);

            if (!$result) {
                throw new RuntimeException('Failed to update user');
            }

            return [
                'success' => true,
                'message' => 'User updated successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kullanıcı silme iş mantığı.
     */
    public function deleteUser($userId)
    {
        try {
            // Kullanıcı var mı kontrol et
            $user = $this->user_model->get(['id' => $userId]);
            if (!$user) {
                throw new InvalidArgumentException('User not found');
            }

            // Admin kullanıcı silme kontrolü
            if ($user->role_id == 1) { // Admin role ID
                throw new InvalidArgumentException('Cannot delete admin user');
            }

            // Soft delete
            $result = $this->user_model->update(['id' => $userId], [
                'isActive' => 0,
                'deletedAt' => date('Y-m-d H:i:s'),
            ]);

            if (!$result) {
                throw new RuntimeException('Failed to delete user');
            }

            return [
                'success' => true,
                'message' => 'User deleted successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kullanıcı listesi (pagination ve filtreleme ile).
     */
    public function getUserList($filters = [], $page = 1, $perPage = 20)
    {
        try {
            $offset = ($page - 1) * $perPage;

            // Build where conditions
            $where = ['isActive' => 1];

            if (!empty($filters['search'])) {
                $search = sanitize_search($filters['search']);
                $where['(username LIKE "%' . $search . '%" OR email LIKE "%' . $search . '%" OR full_name LIKE "%' . $search . '%")'] = null;
            }

            if (!empty($filters['role_id'])) {
                $where['role_id'] = intval($filters['role_id']);
            }

            // Get users with pagination
            $users = $this->user_model->get_all($where, 'id DESC', $perPage, $offset);
            $totalCount = $this->user_model->count_all($where);

            // Get user roles for each user
            foreach ($users as &$user) {
                $user->role = $this->user_role_model->get(['id' => $user->role_id]);
            }

            return [
                'success' => true,
                'data' => [
                    'users' => $users,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total_count' => $totalCount,
                        'total_pages' => ceil($totalCount / $perPage),
                    ],
                ],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Email benzersizlik kontrolü
     */
    private function isEmailExists($email)
    {
        $user = $this->user_model->get(['email' => $email]);

        return !empty($user);
    }

    /**
     * Username benzersizlik kontrolü
     */
    private function isUsernameExists($username)
    {
        $user = $this->user_model->get(['username' => $username]);

        return !empty($user);
    }

    /**
     * Kullanıcı doğrulama (login).
     */
    public function authenticateUser($username, $password)
    {
        try {
            // Kullanıcıyı bul
            $user = $this->user_model->get([
                'username' => $username,
                'isActive' => 1,
            ]);

            if (!$user) {
                throw new InvalidArgumentException('Invalid username or password');
            }

            // Password kontrolü
            if (!password_verify($password, $user->password)) {
                throw new InvalidArgumentException('Invalid username or password');
            }

            // Role bilgisini getir
            $user->role = $this->user_role_model->get(['id' => $user->role_id]);

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Authentication successful',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
