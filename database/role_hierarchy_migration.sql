-- Role hierarchy migration
-- Adds support for delegated role relationships.

ALTER TABLE roles
  ADD COLUMN parent_id INT UNSIGNED NULL AFTER description,
  ADD CONSTRAINT fk_roles_parent_id FOREIGN KEY (parent_id) REFERENCES roles(id);
