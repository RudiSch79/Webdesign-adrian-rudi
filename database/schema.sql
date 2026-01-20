CREATE DATABASE snackscout
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE snackscout;

CREATE TABLE users (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  username      VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  avatar_path   VARCHAR(255) NULL,
  is_admin      TINYINT(1) NOT NULL DEFAULT 0,
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
);

CREATE TABLE brands (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name        VARCHAR(120) NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_brands_name (name)
);

CREATE TABLE categories (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name        VARCHAR(120) NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_categories_name (name)
);

CREATE TABLE snacks (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  brand_id    BIGINT UNSIGNED NOT NULL,
  name        VARCHAR(160) NOT NULL,
  description TEXT NULL,

  -- NEW (mandatory snack image)
  image_path  VARCHAR(255) NOT NULL,

  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  CONSTRAINT fk_snacks_brand
    FOREIGN KEY (brand_id) REFERENCES brands(id)
    ON DELETE RESTRICT,

  UNIQUE KEY uq_snacks_brand_name (brand_id, name),
  KEY idx_snacks_name (name),
  KEY idx_snacks_brand (brand_id)
);

CREATE TABLE reviews (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  snack_id    BIGINT UNSIGNED NOT NULL,
  user_id     BIGINT UNSIGNED NOT NULL,

  title       VARCHAR(160) NOT NULL,
  body        TEXT NOT NULL,
  rating      TINYINT UNSIGNED NOT NULL,

  -- NOW mandatory (because you want review images required)
  image_path  VARCHAR(255) NOT NULL,

  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  CONSTRAINT fk_reviews_snack
    FOREIGN KEY (snack_id) REFERENCES snacks(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_reviews_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,

  CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),

  UNIQUE KEY uq_reviews_snack_user (snack_id, user_id),

  KEY idx_reviews_snack (snack_id),
  KEY idx_reviews_user (user_id),
  KEY idx_reviews_created (created_at)
);

CREATE TABLE review_comments (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  review_id   BIGINT UNSIGNED NOT NULL,
  user_id     BIGINT UNSIGNED NOT NULL,
  body        TEXT NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  CONSTRAINT fk_review_comments_review
    FOREIGN KEY (review_id) REFERENCES reviews(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_review_comments_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,

  KEY idx_review_comments_review (review_id),
  KEY idx_review_comments_user (user_id),
  KEY idx_review_comments_created (created_at)
);
