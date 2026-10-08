-- ============================================================
-- PONTO DE VIRADA — Banco de dados MySQL (substitui o Supabase)
-- Rode isso no phpMyAdmin: aba "SQL" > cole tudo > Executar
-- ============================================================

CREATE DATABASE IF NOT EXISTS ponto_de_virada
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ponto_de_virada;

-- ------------------------------------------------------------
-- Usuários (login, cadastro, streak)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  streak_count INT NOT NULL DEFAULT 0,
  last_active_date DATE NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Cronograma de estudos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  slot_key VARCHAR(30) NOT NULL,
  subject VARCHAR(80) NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_slot (user_id, slot_key),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Favoritos (aulas e vídeos)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS favorites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  lesson_id VARCHAR(80) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_lesson (user_id, lesson_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Histórico de simulados (metas semanais + rendimento real)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS simulados_historico (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  modo VARCHAR(20) NOT NULL,
  area_label VARCHAR(80) NULL,
  acertos INT NOT NULL,
  total INT NOT NULL,
  nota INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Pronto! Depois de rodar, confira em "Estrutura" se apareceram
-- as 4 tabelas: users, schedule, favorites, simulados_historico.
-- ============================================================
