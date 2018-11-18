<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181118093044 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adorador_diasemana_hora (adorador_id INT NOT NULL, diasemana_hora_id INT NOT NULL, INDEX IDX_CB458456B28E1A32 (adorador_id), INDEX IDX_CB458456A4CB874B (diasemana_hora_id), PRIMARY KEY(adorador_id, diasemana_hora_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sacerdote_diasemana_hora (sacerdote_id INT NOT NULL, diasemana_hora_id INT NOT NULL, INDEX IDX_3BEC3BFB64FBB983 (sacerdote_id), INDEX IDX_3BEC3BFBA4CB874B (diasemana_hora_id), PRIMARY KEY(sacerdote_id, diasemana_hora_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adorador_diasemana_hora ADD CONSTRAINT FK_CB458456B28E1A32 FOREIGN KEY (adorador_id) REFERENCES adorador (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adorador_diasemana_hora ADD CONSTRAINT FK_CB458456A4CB874B FOREIGN KEY (diasemana_hora_id) REFERENCES diasemana_hora (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sacerdote_diasemana_hora ADD CONSTRAINT FK_3BEC3BFB64FBB983 FOREIGN KEY (sacerdote_id) REFERENCES sacerdote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sacerdote_diasemana_hora ADD CONSTRAINT FK_3BEC3BFBA4CB874B FOREIGN KEY (diasemana_hora_id) REFERENCES diasemana_hora (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE diasemana_hora DROP horario');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE adorador_diasemana_hora');
        $this->addSql('DROP TABLE sacerdote_diasemana_hora');
        $this->addSql('ALTER TABLE diasemana_hora ADD horario VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
