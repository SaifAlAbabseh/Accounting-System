
CREATE TABLE `admins` (
  `admin_id` int(255) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `admin_name` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `is_super_admin` int(1) NOT NULL,
  `has_insert_privilege` int(1) NOT NULL,
  `has_view_edit_privilege` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `products` (
  `product_id` int(255) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `admin_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `buy_price` decimal(10,0) NOT NULL,
  `quantity` int(255) NOT NULL,
  `tax` decimal(10,0) NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `selling_price` decimal(10,0) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `auth_tokens` (
  `auth_token` varchar(255) NOT NULL,
  `admin_id` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;