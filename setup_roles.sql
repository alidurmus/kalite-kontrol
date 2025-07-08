-- User roles setup
INSERT INTO user_roles (id, role_name, permissions, isActive) VALUES 
(1, 'Admin', 'all', 1),
(2, 'Kalite', 'quality', 1),
(3, 'Yönetim', 'management', 1),
(4, 'Planlama', 'planning', 1),
(5, 'Arge', 'research', 1); 