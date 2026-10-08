-- ============================================================
-- PONTO DE VIRADA — Adiciona o sistema de PROFESSOR
-- Cole no phpMyAdmin (aba SQL) e execute. Não apaga dados existentes.
-- ============================================================

-- Cada usuário agora tem um papel: aluno ou professor
ALTER TABLE users ADD COLUMN role ENUM('aluno','professor') NOT NULL DEFAULT 'aluno';

-- Guarda o texto da redação (quando o simulado tem redação) e a
-- correção manual do professor, além da nota automática
ALTER TABLE simulados_historico ADD COLUMN redacao_texto TEXT NULL;
ALTER TABLE simulados_historico ADD COLUMN nota_professor INT NULL;
ALTER TABLE simulados_historico ADD COLUMN feedback_professor TEXT NULL;
ALTER TABLE simulados_historico ADD COLUMN corrigido_em TIMESTAMP NULL;

-- Atividades postadas pelo professor (os alunos veem numa lista)
CREATE TABLE IF NOT EXISTS activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  professor_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  area_label VARCHAR(80) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (professor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- IMPORTANTE: se der erro dizendo que a coluna/tabela "já existe",
-- pode ignorar aquela linha específica e rodar o resto — só significa
-- que você já tinha rodado essa parte antes.
-- ============================================================
